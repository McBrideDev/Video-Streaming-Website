<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Models\VideoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Helper\VideoHelper;

class RatingModel extends Model {

	protected $table = "rating";
	protected $fillable = ['string_id', 'like', 'dislike', 'user_id'];

	public static function get_vote_like($string_id) {
		$data_like = RatingModel::where('string_id', '=', $string_id)->sum('like');
		return $data_like;
	}

	public static function get_vote_dislike($string_id) {
		$data_dislike = RatingModel::where('string_id', '=', $string_id)->sum('dislike');
		return $data_dislike;
	}

	public static function get_percent($string_id) {
		$like = RatingModel::get_vote_like($string_id);
		$dislike = RatingModel::get_vote_dislike($string_id);
		if ($like != 0 or $dislike != 0) {
			$total = $like + $dislike;
			$percent_like = ($like * 100) / $total;
			$percent_dislike = ($dislike * 100) / $total;
			$data = array(
				'percent_like'    => $percent_like,
				'percent_dislike' => $percent_dislike,
				'like'            => $like,
				'dislike'         => $dislike
			);
			//RatingModel::update_rating($string_id, $percent_like);
			return $data;
		} else {
			$data = array(
				'percent_like'    => 0,
				'percent_dislike' => 0,
				'like'            => 0,
				'dislike'         => 0
			);
			//RatingModel::update_rating($string_id, $percent_like = 0);
			return $data;
		}
	}

	public static function update_rating($string_id, $percent_like) {
		$get_video = VideoModel::select('video.id')->where('string_Id', '=', $string_id)->first();
		$update_rating_video = VideoModel::find($get_video->id);
		$update_rating_video->rating = $percent_like;
		$update_rating_video->save();
	}

	public function save(array $options = array()) {
		$data = parent::save($options);
		VideoHelper::removeRatingCache($this->string_id);

		return $data;
	}

	public function delete() {
		$data = parent::delete();
		VideoHelper::removeRatingCache($this->string_id);

		return $data;
	}

	public function update(array $attributes = array()) {
		$data = parent::update($attributes);

		VideoHelper::removeRatingCache($this->string_id);
		return $data;
	}
}
