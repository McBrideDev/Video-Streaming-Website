<?php
namespace App\Helper;

use Illuminate\Support\Facades\Cache;
use App\Models\RatingModel;
use App\Models\VideoCatModel;
use App\Models\VideoModel;
use App\Models\SubsriptionModel;
use App\Helper\AppHelper;
use DB;

/**
 * helper class for all events through the app
 */
class VideoHelper {
	const CACHE_RATING_PREFIX = 'VIDEO_RATING_';
	const CACHE_DETAIL_PREFIX = 'VIDEO_DETAIL_';
	const CACHE_PURCHASED_LIST_PREFIX = 'PURCHASED_LIST_';

	/**
	 * cache for single video
	 *
	 * @param type $videoId
	 */
	public static function getRating($videoId) {
		$cacheKey = self::CACHE_RATING_PREFIX . $videoId;

		return Cache::remember($cacheKey, AppHelper::DEFAULT_CACHE_IN_MINUTE, function() use ($videoId) {
			$like = RatingModel::get_vote_like($videoId);
			$dislike = RatingModel::get_vote_dislike($videoId);

			if ($like != 0 or $dislike != 0) {
				$total = $like + $dislike;
				$percent_like = ($like * 100) / $total;
				$percent_dislike = ($dislike * 100) / $total;
				return array(
					'percent_like'    => $percent_like,
					'percent_dislike' => $percent_dislike,
					'like'            => $like,
					'dislike'         => $dislike
				);
			}

			return array(
				'percent_like'    => 0,
				'percent_dislike' => 0,
				'like'            => 0,
				'dislike'         => 0
			);
		});
	}

	/**
	 * remove single cache
	 *
	 * @param String $videoStringId
	 */
	public static function removeRatingCache($videoStringId) {
		$cacheKey = self::CACHE_RATING_PREFIX . $videoStringId;
		Cache::forget($cacheKey);
	}

	/**
	 * get random video in the category
	 * @param type $categoryId
	 * @return type
	 */
	public static function getRandomByCategoryId($categoryId, $size = 4) {
		//get random 4 videos in the category model then find detail
		$videos = VideoCatModel::where('cat_id', '=', $categoryId)
						->join(
							DB::raw("(select (rand() * (select max(id) from `table_video_cat`)) as id ) as r2"), 'video_cat.id', '>=', DB::raw('r2.id')
						)
						->take($size)
						// ->OrderbyRaw('RAND()')
						->get(['video_id'])
						->toArray();

		$randomVideos = [];
		foreach($videos as $v) {
			$video = self::getVideoDetail($v['video_id']);
			if (is_null($video))
				$video = VideoModel::where('string_Id', '=', $v['video_id'])->get();

			$randomVideos[] = $video;
		}

		return $randomVideos;

		// $ids = [];
		// foreach($videos as $v) {
		// 	$ids[] = $v['video_id'];
		// }
		// return VideoModel::where('status', '=', 1)
		// 			->whereIn('string_Id', $ids)
		// 			->get();
	}

	public static function removeDetailCache($videoId) {
		$cacheKey = self::CACHE_DETAIL_PREFIX . $videoId;
		Cache::forget($cacheKey);
	}

	/**
	 * single cache key
	 * @param type $videoId
	 * @return type
	 */
	public static function getDetailCacheKey($videoId) {
		return self::CACHE_DETAIL_PREFIX . $videoId;
	}

	/**
	 * get url of video profile
	 *
	 * @param String $videoId
	 * @param String $userId
	 * @return String
	 */
	public static function getAuthorLink($videoId, $userId = null) {
		$check_author = VideoModel::select('video.string_Id', 'video.user_id', 'members.firstname', 'members.lastname', 'members.avatar')
						->where('video.string_Id', '=', $videoId)
						->join('members', 'members.user_id', '=', 'video.user_id')
						->first();

		if ($check_author && $check_author->user_id) {
			return $check_author->user_id == $userId ?
				$link = URL(getLang() . 'member-proflie.html') :
				URL(getLang() . 'profile/' . $check_author->user_id . '/' . str_slug($check_author->firstname . ' ' . $check_author->lastname) . '.html');
		}

		return null;
		// return Cache::remember('VIDEO_AUTHOR_LINK_' . $videoId, AppHelper::DEFAULT_CACHE_IN_MINUTE, function() use ($videoId, $userId) {
		// 	$check_author = VideoModel::select('video.string_Id', 'video.user_id', 'members.firstname', 'members.lastname', 'members.avatar')
		// 					->where('video.string_Id', '=', $videoId)
		// 					->join('members', 'members.user_id', '=', 'video.user_id')
		// 					->first();

		// 	if ($check_author && $check_author->user_id) {
		// 		return $check_author->user_id == $userId ?
		// 			$link = URL(getLang() . 'member-proflie.html') :
		// 			URL(getLang() . 'profile/' . $check_author->user_id . '/' . str_slug($check_author->firstname . ' ' . $check_author->lastname) . '.html');
		// 	}

		// 	return null;
		// });
	}

	/**
	 * get author of video
	 *
	 * @param type $stringId
	 * @return type
	 */
	public static function getAuthor($stringId) {
		//cache in 60min
		$cacheKey = 'VIDEO_AUTHOR_' . $stringId;
		return Cache::remember($cacheKey, AppHelper::DEFAULT_CACHE_IN_MINUTE, function() use($stringId) {
			$video = VideoHelper::getVideoDetail($stringId);
			if (!$video || !$video->user_id) {
				return null;
			}

			return VideoModel::select('video.string_Id', 'video.user_id', 'members.firstname', 'members.lastname', 'members.avatar')
						->where('video.string_Id', '=', $stringId)
						->join('members', 'members.user_id', '=', 'video.user_id')
						->first();
		});
	}

	/**
	 * retrieve video from cache
	 * @param type $id
	 * @return type
	 */
	public static function getVideoDetail($id) {
		return Cache::remember(VideoHelper::getDetailCacheKey($id), AppHelper::DEFAULT_CACHE_IN_MINUTE, function() use ($id) {
			return VideoModel::where('string_Id', '=', $id)->first();
		});
	}

	/**
	 * check user is bought this video
	 * @param String $userId
	 * @param String $videoId
	 * @return boolean
	 */
	public static function isPurchased($userId, $videoId) {
		$cacheKey = self::CACHE_PURCHASED_LIST_PREFIX. $userId;
		$list = Cache::remember($cacheKey, AppHelper::DEFAULT_CACHE_IN_MINUTE, function() use ($userId) {
			$items = SubsriptionModel::where('user_id', '=', $userId)->get();

			$ids = [];
			foreach($items as $item) {
				$ids[] = $item->video_id;
			}
			return [];
		});

		return in_array($videoId, $list);
	}

	/**
	 * clear cache for single user
	 * @param type $userId
	 */
	public static function clearPurchasedCache($userId) {
		Cache::forget(self::CACHE_PURCHASED_LIST_PREFIX. $userId);
	}

	public static function getImageUrl($poster) {
		$file_headers = @get_headers($poster);
		if ($file_headers[0] == 'HTTP/1.1 404 Not Found' || $file_headers[0] == 'HTTP/1.0 404 Not Found') {
			return asset('public/assets/images/no-image.jpg');
		}
		return $poster;
	}
}
