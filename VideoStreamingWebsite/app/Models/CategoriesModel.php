<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use File;
use \Illuminate\Support\Facades\DB;
use App\Helper\AppHelper;
use App\Models\VideoCatModel;

class CategoriesModel extends Model {

	protected $table = "categories";

	const ACTIVE = 1;
	const INACTIVE = 0;
	const APPROVED = 2;
	const RECOMMENT_ACTIVE = 1;
	const RECOMMENT_INACTIVE = 0;

	protected $fillable = ['title_name', 'post_name', 'poster', 'description', 'status', 'recomment'];

	public static function video_cat($id) {
		return VideoModel::where('status', '=', 1)
					->whereIn('string_Id', function($query) use ($id)
						{
							$query->select('video_id')
										->from(VideoCatModel::$tableName)
										->whereRaw("cat_id = {$id}");
						})
					->join(
						DB::raw("(select (rand() * (select max(id) from `table_video`)) as id ) as r2"), 'video.id', '>=', DB::raw('r2.id')
					)
					// ->OrderbyRaw('RAND()')
					->paginate(perPage());

	}

	public static function video_cat_order_rate($id, $fist, $end) {
		$video_id = array();
		$get_video_id = \App\Models\VideoCatModel::where('cat_id', '=', $id)->get();
		for ($i = 0; $i < count($get_video_id); $i++) {
			array_push($video_id, $get_video_id[$i]->video_id);
		}
		$video_array = \App\Models\VideoModel::where('status', '=', 1)
						->whereRaw("duration BETWEEN " . $fist . " and " . $end . "")
						->whereIn('string_Id', $video_id)
						->orderby('rating', 'DESC')
						->paginate(perPage());
		return $video_array;
	}

	public static function video_cat_order_new($id, $fist, $end) {
		$video_id = array();
		$get_video_id = \App\Models\VideoCatModel::where('cat_id', '=', $id)->get();
		for ($i = 0; $i < count($get_video_id); $i++) {
			array_push($video_id, $get_video_id[$i]->video_id);
		}
		$video_array = \App\Models\VideoModel::where('status', '=', 1)
						->whereRaw("duration BETWEEN " . $fist . " and " . $end . "")
						->whereIn('string_Id', $video_id)
						->paginate(perPage());
		return $video_array;
	}

	public static function video_cat_order_viewed($id, $fist, $end) {
		$video_id = array();
		$get_video_id = \App\Models\VideoCatModel::where('cat_id', '=', $id)->get();
		for ($i = 0; $i < count($get_video_id); $i++) {
			array_push($video_id, $get_video_id[$i]->video_id);
		}
		$video_array = \App\Models\VideoModel::where('status', '=', 1)
						->whereRaw("duration BETWEEN " . $fist . " and " . $end . "")
						->whereIn('string_Id', $video_id)
						->orderby('total_view', 'DESC')
						->paginate(perPage());
		return $video_array;
	}

	public static function getImageUrl($poster) {
		if (!empty($poster) && File::exists(public_path() . '/upload/categories/' . $poster)) {
			$image = asset('public/upload/categories') . "/" . $poster;
		} else {
			$image = asset('public/assets/images/no-image.jpg');
		}
		return $image;
	}

	public function save(array $options = array()) {
		$data = parent::save($options);

		AppHelper::clearCategoryCache();
		return $data;
	}

	public function delete() {
		$data = parent::delete();

		AppHelper::clearCategoryCache();
		return $data;
	}

	public function update(array $attributes = array()) {
		$data = parent::update($attributes);

		AppHelper::clearCategoryCache();
		return $data;
	}
}
