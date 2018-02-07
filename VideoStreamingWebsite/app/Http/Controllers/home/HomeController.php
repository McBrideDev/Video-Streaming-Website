<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use App\Models\VideoModel;
use App\Models\CategoriesModel;
use App\Helper\AppHelper;
use App\Helper\VideoHelper;
use Illuminate\Support\Facades\Cache;
use DB;

class HomeController extends Controller {

	public function get_indexnew() {
		$today      = [];
		$todoRating = [];

		$today_post = VideoModel::where('status', '=', VideoModel::STATUS_COMPLETED)
						->where('created_at', 'like', '' . date('Y-m-d') . '%')
						->groupBy('post_name')
						->groupBy('duration')
						->orderby('created_at', 'DESC')
						->paginate(perPage());

		if (count($today_post) > 0) {
			$today = $today_post;
			if ($today->currentPage() >= 2) {
				return redirect(getLang() . 'rating-video/?page=' . $today->currentPage() . '');
			}
		}

		// dd($today);

		$today_rating = VideoModel::where('status', '=', VideoModel::STATUS_COMPLETED)
						->orderby('total_view', 'DESC')
						->paginate(38);

		if (count($today_rating) > 0) {
			$todoRating = $today_rating;
			if ($todoRating->currentPage() >= 2) {
				return redirect(getLang() . 'views/?page=' . $todoRating->currentPage() . '');
			}
		}

		// dd($todoRating);

		//cache in an hour
		$indexnew = Cache::remember(AppHelper::CACHE_FEATURED_VIDEO_HOME_LIST, 60, function() {
			return VideoModel::where('status', '=', VideoModel::STATUS_COMPLETED)->where('featured', '=', '1')
						->OrderBy('created_at', 'DESC')
						->take(12)
						->get();
		});

		// $get_watch_now = VideoModel::select('video.*', 'watch_now.video_id')
		//         ->join('watch_now', 'watch_now.video_id', '=', 'video.string_Id')
		//         ->where('video.status', '=', '1')
		//         ->take(6)
		//         ->groupBy('video.post_name')
		//         ->groupBy('video.duration')
		//         ->OrderbyRaw('RAND()')
		//         ->get();

		$get_watch_now = VideoModel::select('video.*', 'watch_now.video_id')
			->join('watch_now', 'watch_now.video_id', '=', 'video.string_Id')
			->join(
				DB::raw("(select (rand() * (select max(id) from `table_watch_now`)) as id ) as r2"), 'watch_now.id', '>=', DB::raw('r2.id')
			)
			->where('video.status', '=', VideoModel::STATUS_COMPLETED)
			->groupBy('video.post_name')
			->groupBy('video.duration')
			->orderby('watch_now.time', 'DESC')
			->orderby('watch_now.updated_at', 'DESC')
			->orderby('watch_now.created_at', 'DESC')
			->paginate(12);

		$recommentVideos = [];

		$recomment = $this->get_recommentvideo();
		for($i = 0; $i < count($recomment); $i ++) {
			$recommentVideos[$i]['category']['url'] = URL(getLang().'categories/') . "/" . $recomment[$i]->id . "." . $recomment[$i]->post_name . ".html";
			$recommentVideos[$i]['category']['title'] = $recomment[$i]->title_name;

			$video_categories = VideoHelper::getRandomByCategoryId($recomment[$i]->id, 6);
			if (!empty($video_categories)) {
				for($j = 0; $j < count($video_categories); $j++) {
					if(!empty($video_categories[$j])) {
						$video = $video_categories[$j]->toArray();
						if(!empty($video)) {
							$rating = isset($video["string_Id"]) ? VideoHelper::getRating($video["string_Id"]) : [];
							$recommentVideos[$i]['videos'][$j] = $video;
							$recommentVideos[$i]['videos'][$j]['rating'] = $rating;
						}
					}
				}
			}
		}

		// dd($recommentVideos);

		$categories = AppHelper::getCategoryList();

		return view('home.home')->with('indexnew', $indexnew)
								->with('watch_now', $get_watch_now)
								->with('categories', $categories)
								->with('today', $today)
								->with('todayRating', $todoRating)
								->with('recommentVideos', $recommentVideos);
	}

	public function get_recommentvideo() {
		return CategoriesModel::select('categories.*', 'video_cat.video_id', 'video_cat.cat_id')
						->where('categories.recomment', '=', 1)
						->join('video_cat', 'video_cat.cat_id', '=', 'categories.ID')
						->join(
							DB::raw("(select (rand() * (select max(id) from `table_video_cat`)) as id ) as r2"), 'video_cat.id', '>=', DB::raw('r2.id')
						)
						->take(4)
						// ->OrderbyRaw("RAND()")
						->groupBy('categories.ID')
						->get();
	}

	public function get_categories() {
		return AppHelper::getCategoryList();
	}

	public function get_order_views() {
		$video_views = VideoModel::where('status', '=', VideoModel::STATUS_COMPLETED)->orderby('total_view', 'DESC')->paginate(perPage());
		return view('home.views')->with('views', $video_views);
	}

	public function get_order_duration() {
		$video_views = VideoModel::where('status', '=', VideoModel::STATUS_COMPLETED)->orderby('duration', 'DESC')->paginate(perPage());
		return view('home.duration')->with('duration', $video_views);
	}

	public function get_order_rating() {
		$video_views = VideoModel::where('status', '=', VideoModel::STATUS_COMPLETED)->orderby('rating', 'DESC')->paginate(perPage());

		return view('home.rating')->with('rating_view', $video_views);
	}

	public function get_order_date() {
		$video_views = VideoModel::where('status', '=', VideoModel::STATUS_COMPLETED)->orderby('created_at', 'DESC')->paginate(perPage());
		return view('home.date')->with('date_view', $video_views);
	}

}
