<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VideoModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class PornStarModel extends Model {

	protected $table = "pornstar";

	const ACTIVE = 1;
	const INACTIVE = 0;

	protected $fillable = ['title_name', 'post_name', 'poster', 'wall_poster', 'description', 'age', 'gender', 'born', 'height', 'ethnicity', 'hair_color', 'eye_color', 'status', 'subscribe_status', 'total_view', 'tag'];

	const CACHE_DETAIL_PREFIX = 'PORN_STAR_';

	public static function getVideoByPornStarId($id) {
		$video = VideoModel::where('pornstar_Id', '=', $id)->count();
		if ($video == 1 or $video == 0) {
			return $video . " Video";
		}
		return $video . " Videos";
	}

	public static function CountPornStarVideo($id) {
		$video = VideoModel::where('pornstar_Id', '=', $id)->count();
		if (!empty($video)) {
			return $video;
		}
		return 0;
	}

	public static function check_thumb($id) {
		$get_thumb = PornStarModel::find($id);
		if ($get_thumb != NULL) {
			if ($get_thumb->poster == NULL or file_exists(public_path() . '/upload/pornstar/' . $get_thumb->poster) === false) {
				return asset('public/assets/images/no-image.jpg');
			}
			return asset('public/upload/pornstar/' . $get_thumb->poster);
		}
		return asset('public/assets/images/no-image.jpg');
	}

	public static function check_wall($id) {
		$get_thumb = PornStarModel::find($id);
		if ($get_thumb != NULL) {
			if ($get_thumb->wall_poster == NULL or file_exists(public_path() . '/upload/pornstar/' . $get_thumb->wall_poster) === false) {
				return asset('public/upload/pornstar/Pornstar_Wall_Poster_Amateur.jpg');
			}
			return asset('public/upload/pornstar/' . $get_thumb->wall_poster);
		}
		return asset('public/upload/pornstar/Pornstar_Wall_Poster_Amateur.jpg');
	}

	public static function get_total_video_view($id) {
		$video_sum_view = VideoModel::where('pornstar_Id', '=', $id)->get()->sum('total_view');
		//var_dump($video_sum_view);die;
		if ($video_sum_view > 0) {
			return $video_sum_view;
		} else {
			return 0;
		}
	}

	private function clearCache() {
		Cache::forget(self::CACHE_DETAIL_PREFIX . $this->id);
	}

	public function save(array $options = array()) {
		$data = parent::save($options);

		$this->clearCache();
		return $data;
	}

	public function delete() {
		$data = parent::delete();

		$this->clearCache();
		return $data;
	}

	public function update(array $attributes = array()) {
		$data = parent::update($attributes);

		$this->clearCache();
		return $data;
	}

	public static function removeById($id) {
		$pornStar = PornStarModel::find ($id);
		if(!empty($pornStar)){
			if(!empty($pornStar->poster)){
				if(File::exists(public_path()."/upload/pornstar/".$pornStar->poster)){
					unlink(public_path()."/upload/pornstar/".$pornStar->poster);
				}
			}
			if(!empty($pornStar->wall_poster)){
				if(File::exists(public_path()."/upload/pornstar/".$pornStar->wall_poster)){
					unlink(public_path()."/upload/pornstar/".$pornStar->wall_poster);
				}
			}
			if($pornStar->delete()) {
				return true;
			}
			return false;
		}
		return false;
	}
}
