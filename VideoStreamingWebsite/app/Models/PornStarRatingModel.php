<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PornStarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PornStarRatingModel extends Model  {

	protected $table="pornstar_rating";
	protected $fillable = ['pornstar_id', 'like', 'dislike', 'user_id'];
	public static function get_vote_like($pornstar_id){
		$data_like= PornStarRatingModel::where('pornstar_id','=',$pornstar_id)->get()->sum('like');
		return $data_like;
	}
	public static function get_vote_dislike($pornstar_id){
		$data_dislike= PornStarRatingModel::where('pornstar_id','=',$pornstar_id)->get()->sum('dislike');
		return $data_dislike;
	}
	public static function total_votes($pornstar_id){
		$total_votes=PornStarRatingModel::get_vote_like($pornstar_id)+PornStarRatingModel::get_vote_dislike($pornstar_id);
		return $total_votes;
	}
	public static function get_percent($pornstar_id){
		$like=PornStarRatingModel::get_vote_like($pornstar_id);
		$dislike=PornStarRatingModel::get_vote_dislike($pornstar_id);
		if($like!=0 or $dislike!=0 ){
		$total= $like+$dislike;
		$percent_like=($like*100)/$total;
		$percent_dislike=($dislike*100)/$total;
		$data=array(
			'percent_like'=>$percent_like,
			'percent_dislike'=>$percent_dislike,
			'like'=>$like,
			'dislike'=>$dislike
		);
		return $data;
		}else{
			$data=array(
				'percent_like'=>0,
				'percent_dislike'=>0,
				'like'=>0,
				'dislike'=>0
			);
			return $data;
		}

		
	}

}
