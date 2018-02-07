<?php

namespace App\Http\Controllers\pornstar;

use App\Http\Controllers\Controller;
use App\Models\PornStarModel;
use App\Models\PornStarRatingModel;
use App\Models\PornStarPhotoModel;
use App\Models\VideoModel;
use App\Helper\PornStarHelper;
use App\Helper\AppHelper;
use Illuminate\Support\Facades\DB;

class PornStarController extends Controller {

	public function get_pornstar() {

		$count = PornStarHelper::numberPornStars();
		$skip = 4;
		$limit = $count - $skip;

		$pornstar = PornStarModel::select('pornstar.*', DB::raw('count(table_video.pornstar_Id) as video_numbers'))
						->leftJoin('video', 'pornstar.id', '=', 'video.pornstar_Id')
						->where('pornstar.status', '=', '1')
						->groupBy('pornstar.id')
						->orderby('created_at', 'DESC')
						->paginate(perPage());
		$categories = AppHelper::getCategoryList();
		if ($categories) {
			return view('pornstars.pornstars')
						->with('categories', $categories)
						->with('pornstar', $pornstar);
		}
	}

	public function get_filter($key) {
		if (!empty($key)) {
			$count = PornStarHelper::numberPornStars();
			$skip = 4;
			$limit = $count - $skip;
			$pornstar = PornStarModel::where('status', '=', '1')
							->orderby('created_at', 'DESC')
							->where(function($q) use ($key){
								if($key == 'specialKey') {
									$q->whereRaw("title_name REGEXP '^[^A-Za-z]'");
								} else {
									$q->where('title_name', 'like', '' . $key . '%');
								}
							})
							->paginate(perPage());
			// $pornstar = PornStarModel::where('status', '=', '1')->orderby('created_at', 'DESC')
			// 				->where('pornstar.title_name', 'like', '' . $key . '%')
			// 				->paginate(perPage());
			if (count($pornstar) > 0) {
				return view('pornstars.pornstarsfilter')
												->with('pornstar', $pornstar)
												->with('keys', $key);
			} else {
				return view('pornstars.pornstarsfilter')
												->with('msg_filter', 'There are currently no pornstars in our directory that have a name that start with this letter');
			}
		} else {
			return 0;
		}
	}

	public function get_pornstar_video($id) {
		if ($id != NULL) {
			$categories = AppHelper::getCategoryList();
			$total_video = PornStarModel::CountPornStarVideo($id);
			$get_video = VideoModel::where('pornstar_Id', '=', $id)->paginate(perPage());
			$get_pornstar = PornStarModel::where('ID', '=', $id)->first();
			$pornstar_like = PornStarRatingModel::get_vote_like($id);
			$pornstar_dislike = PornStarRatingModel::get_vote_dislike($id);
			$percent_rating = PornStarRatingModel::get_percent($id);
			$check_thumb = PornStarModel::check_thumb($id);
			$check_wall = PornStarModel::check_wall($id);
			$total_photo = PornStarPhotoModel::where('pornstar_id', '=', $id)->count();
			return view('pornstars.onepornstars')
						->with('pornstar_video', $get_video)
						->with('categories', $categories)
						->with('pornstar', $get_pornstar)
						->with('pornstar_like', $pornstar_like)
						->with('pornstar_dislike', $pornstar_dislike)
						->with('percent_rating', $percent_rating)
						->with('total_video', $total_video)
						->with('check_thumb', $check_thumb)
						->with('total_votes', PornStarRatingModel::total_votes($id))
						->with('sum_view', PornStarModel::get_total_video_view($id))
						->with('total_photo', $total_photo)
						->with('check_wall', $check_wall);
		} else {
			return redirect(getLang() . 'porn-stars.html');
		}
	}

	public function get_rating($vote, $pornstar_id) {
		if (\Session::has('User')) {
			$user = \Session::get('User');

			$checkmember_voting = PornStarRatingModel::where('pornstar_id', '=', $pornstar_id)->where('user_id', '=', $user->user_id)->first();
			if ($checkmember_voting != NULL) {
				$like = PornStarRatingModel::get_vote_like($pornstar_id);
				$dislike = PornStarRatingModel::get_vote_dislike($pornstar_id);
				$total = $like + $dislike;
				$percent_like = ($like * 100) / $total;
				$percent_dislike = ($dislike * 100) / $total;
				$data = array(
					'like'         => $like,
					'dislike'      => $dislike,
					'percent_like' => $percent_like,
					'msg'          => 'Already voted'
				);
				return $data;
			} else {
				if ($vote == "like") {
					$addvote = new PornStarRatingModel();
					$addvote->pornstar_id = $pornstar_id;
					$addvote->user_id = $user->user_id;
					$addvote->like = 1;
					if ($addvote->save()) {
						$like = PornStarRatingModel::get_vote_like($pornstar_id);
						$dislike = PornStarRatingModel::get_vote_dislike($pornstar_id);
						$total = $like + $dislike;
						$percent_like = ($like * 100) / $total;
						$percent_dislike = ($dislike * 100) / $total;
						$data = array(
								'like' => $like,
								'dislike' => $dislike,
								'percent_like' => $percent_like,
								'percent_dislike' => $percent_dislike,
								'msg' => ''
						);

						return $data;
					}
				} else {
					$addvote = new PornStarRatingModel();
					$addvote->pornstar_id = $pornstar_id;
					$addvote->user_id = $user->user_id;
					$addvote->dislike = 1;
					if ($addvote->save()) {
						$like = PornStarRatingModel::get_vote_like($pornstar_id);
						$dislike = PornStarRatingModel::get_vote_dislike($pornstar_id);

						$total = $like + $dislike;
						$percent_like = ($like * 100) / $total;
						$percent_dislike = ($dislike * 100) / $total;
						$data = array(
								'like' => $like,
								'dislike' => $dislike,
								'percent_like' => $percent_rating,
								'percent_dislike' => $percent_dislike,
								'msg' => ''
						);

						return $data;
					}
				}
			}
		} else {
			$like = PornStarRatingModel::get_vote_like($pornstar_id);
			$dislike = PornStarRatingModel::get_vote_dislike($pornstar_id);
			$total = $like + $dislike;
			$percent_like = 0;
			$percent_dislike = 0;
			$data = array(
				'like'         => $like,
				'dislike'      => $dislike,
				'percent_like' => $percent_like,
				'msg'          => '<a data-toggle="modal" data-target="#myModal" style="color:#ee577c" href="#">' . trans('home.LOGIN_TO_VOTE') . '</a>'
			);
			return $data;
		}
	}

	public function get_ajx_pornstar_video($id) {
		$get_pornstar = PornStarModel::where('ID', '=', $id)->first();
		$get_video = VideoModel::where('pornstar_Id', '=', $id)->paginate(perPage());
		if (\Request::ajax()) {
			return view('pornstars.porn_result')->with('video', $get_video)->with('pornstar_video', "" . $get_pornstar->title_name . "'s " . trans('home.VIDEOS'));
		} else {
			return redirect(getLang() . 'pornstars/' . $id . '/' . $get_pornstar->post_name . '');
		}
	}

	public function get_ajx_pornstar_photo($id) {
		$get_pornstar = PornStarModel::where('ID', '=', $id)->first();
		$get_video = PornStarPhotoModel::where('pornstar_Id', '=', $id)->paginate(perPage());
		if (\Request::ajax()) {
			return view('pornstars.porn_result')->with('photo', $get_video)->with('pornstar_photo', '' . $get_pornstar->title_name . ' ' . trans('home.PHOTOS'));
		} else {
			return redirect(getLang() . 'pornstars/' . $id . '/' . $get_pornstar->post_name . '');
		}
	}

}
