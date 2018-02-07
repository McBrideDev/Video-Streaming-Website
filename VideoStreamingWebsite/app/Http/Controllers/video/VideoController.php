<?php

namespace App\Http\Controllers\video;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\VideoModel;
use App\Models\ChannelModel;
use App\Models\ChanneSubscriberModel;
use App\Models\MemberVideoModel;
use App\Models\MemberModel;
use App\Models\CategoriesModel;
use App\Models\StandardAdsModel;
use App\Models\VideoCommentModel;
use App\Models\VideoCatModel;
use App\Models\ConversionModel;
use App\Models\RatingModel;
use App\Models\OptionModel;
use App\Models\WatchNowModel;
use App\Models\PornStarModel;
use App\Models\StaticPageModel;
use App\Models\SubsriptionModel;
use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Helper\AppHelper;
use App\Helper\VideoHelper;
use App\Helper\AdsHelper;
use App\Services\TimeService;
use Input;
use DateTime;
use Exception;
use Log;

class VideoController extends Controller {

	public function get_view($string_Id, $name) {

		$detailview = VideoHelper::getVideoDetail($string_Id);

		if(is_null($detailview)) {
			return view('errors.404');
		}

		$check_watch = WatchNowModel::where('video_id', '=', $string_Id)->first();

		$video_id = $string_Id;
		$time = time();
		if ($check_watch != NULL) {
			WatchNowModel::where('video_id', '=', $string_Id)->update(array('time' => $time));
		} else {
			$add_watch = new WatchNowModel();
			$add_watch->video_id = $video_id;
			$add_watch->time = $time;
			$add_watch->save();
		}

// dd($this->get_link_member($string_Id));
        $tag = array_filter($this->get_tag($string_Id));
		if ($detailview->status == VideoModel::STATUS_COMPLETED) {
			return view('video.view')->with('viewvideo', $detailview)
						->with('categories', AppHelper::getCategoryList())
						->with('related', $this->get_relatedvideo($detailview->id, $detailview->categories_Id))
						->with('recomment', $this->get_recommentvideo($detailview->id))
						->with('share', 'HELLO')
						->with('getcomment', $this->get_comment($string_Id))
						->with('countcomment', $this->get_countcomment($string_Id))
						->with('videoads', $this->get_ads())
						->with('percent_like', $this->get_percent_like($string_Id))
						->with('tag', $tag)
						->with('author_post', $this->get_video_author_upload($string_Id))
						->with('user_subscribe', $this->check_subscribe($detailview->channel_Id))
						->with('author_link', $this->get_link_member($string_Id))
						->with('channel_name', $this->get_channgel_name($detailview->channel_Id))
						->with('pornstar_name', $this->get_pornstar_name($detailview->pornstar_Id))
						->with('check_favorite', $this->check_favorited($string_Id))
						->with('count_favorite', $this->count_favorited($string_Id));
		} else if ($detailview->status == VideoModel::BLOCKED) {
			return redirect(getLang() . 'member-proflie.html')->with('msg', 'Message: Video has been blocked by Administrator. Please contact with Administrator to unblock this video!');
		} else if ($detailview->status == VideoModel::CONVERT_STATUS) {
			return redirect('')->with('msg', 'Message: This video is not available now. Please wait or contact with Administrator!');
		}
	}

	public function postVideoRelate(Request $get) {
		$videoid = $get->only('videoId');
		$related = VideoModel::where('status', '=', VideoModel::STATUS_COMPLETED)
						->join(
							DB::raw("(select (rand() * (select max(id) from `table_video`)) as id ) as r2"), 'video.id', '>=', DB::raw('r2.id')
						)
						->where('id', '<>', $videoid)
						->take(4)
						// ->orderByRaw("RAND()")
						->get();
		return view('video.videoRelate')->with('related', $related);
	}

	public function getLoadAds() {
		$loadAds = StandardAdsModel::where('position', '=', 'video')->take(1)->orderByRaw('RAND()')->first();
		return view('video.videoAdvertisement', compact('loadAds'));
	}

	public function get_channgel_name($channelId) {
		$cacheKey = ChannelModel::CACHE_DETAIL_PREFIX . $channelId;

		return Cache::remember($cacheKey, AppHelper::DEFAULT_CACHE_IN_MINUTE, function() use ($channelId) {
			ChannelModel::where('id', '=', $channelId)->where('status', '=', '1')->first();
		});
	}

	public function get_pornstar_name($pornStarId) {
		$cacheKey = PornStarModel::CACHE_DETAIL_PREFIX . $pornStarId;

		return Cache::remember($cacheKey, AppHelper::DEFAULT_CACHE_IN_MINUTE, function() use ($pornStarId) {
			return PornStarModel::where('id', '=', $pornStarId)->where('status', '=', '1')->first();
		});
	}

	public function get_percent_like($string_id) {
		return RatingModel::get_percent($string_id);
	}

	public function get_ads() {
		return AdsHelper::getActiveAdsTextList();
	}

	public function get_categories() {
		return AppHelper::getCategoryList();
	}

	public function get_relatedvideo($video_id, $categories_id) {
		if (!empty($video_id)) {
			$related = VideoModel::where('status', '=', VideoModel::STATUS_COMPLETED)
							->join(
								DB::raw("(select (rand() * (select max(id) from `table_video`)) as id ) as r2"), 'video.id', '>=', DB::raw('r2.id')
							)
							->where('video.id', '<>', $video_id)
							->take(8)
							// ->orderByRaw("RAND()")
							->get();

			return $related;
		}
		return;
	}

	public function get_recommentvideo($video_id) {
		$recomment = VideoModel::select('video.*', 'categories.id', 'categories.recomment')
						->where('video.status', '=', VideoModel::STATUS_COMPLETED)
						->where('categories.status', '=', '1')
						->where('video.id', '<>', $video_id)
						->join('categories', 'video.categories_Id', '=', 'categories.id')
						->join(
							DB::raw("(select (rand() * (select max(id) from `table_video`)) as id ) as r2"), 'video.id', '>=', DB::raw('r2.id')
						)
						->take(4)
						// ->orderByRaw('RAND()')
						->get();
		return $recomment;
	}

	public function get_curl_header($url) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_exec($ch);
		$headers = curl_getinfo($ch);
		curl_close($ch);

		return $headers;
	}

	public function download_curl($url, $path) {
		# open file to write
		$fp = fopen($path, 'w+');
		# start curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		# set return transfer to false
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		# increase timeout to download big file
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		# write data to local file
		curl_setopt($ch, CURLOPT_FILE, $fp);
		# execute curl
		curl_exec($ch);
		# close curl
		curl_close($ch);
		# close local file
		fclose($fp);

		if (filesize($path) > 0)
			return true;
	}

	public function get_download($name, $string_Id) {
		if (\Session::has('User')) {
			$videodownload = VideoModel:: where('string_Id', '=', $string_Id)->first();
			// $extension = pathinfo($videodownload->video_src, PATHINFO_EXTENSION);
			// $extension = substr($videodownload->video_src, 70);
			$url = '' . $videodownload->video_src . '';
			$path = public_path() . '/upload/video/' . $string_Id . '.' . $videodownload->extension;

			$headers = $this->get_curl_header($url);

			if ($headers['http_code'] === 200 and $headers['download_content_length'] > 1024) {
				if ($this->download_curl($url, $path)) {
					$loadfile = \Response::download($path);
					return $loadfile;
				}
			}
			return 'download not completed';
		}
		return view('video.download')->with('msg', trans('home.PLEASE_LOGIN_OR_SIGNUP_FOR_USE'));
	}

	public function get_favorite($name, $string_Id) {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$getvideo = VideoModel::where('string_Id', '=', $string_Id)->first();
			$checkmember = MemberVideoModel::where('member_Id', '=', $user->user_id)->first();
			if ($checkmember == NULL) {
				$memberfavorite = new MemberVideoModel();
				$memberfavorite->member_Id = $user->user_id;
				$memberfavorite->video_Id = $string_Id;
				$memberfavorite->status = 1;
				if ($memberfavorite->save()) {
					$countNumber = MemberVideoModel::where('video_Id', 'LIKE', '%'. $string_Id .'%')->count();
					$msg = 'Video "' . $getvideo->title_name . '" has been added to your collection ';
					return json_encode([ 'message' => $msg, 'number' => $countNumber ]);
				}
			} else {
				$videoid_array = explode(',', $checkmember->video_Id);
				if (in_array($string_Id, $videoid_array)) {
					$msg = 'Video ' . $getvideo->title_name . ' is already in your collection';
					return json_encode([ 'message' => $msg ]);
				} else {
					$updatevideo = MemberVideoModel::where('member_Id', '=', $user->user_id)
									->update(array('video_Id' => $checkmember->video_Id . ',' . $string_Id));
					if ($updatevideo) {
						$countNumber = MemberVideoModel::where('video_Id', 'LIKE', '%'. $string_Id .'%')->count();
						$msg = 'Video "' . $getvideo->title_name . '" has been added to your collection';
						return json_encode([ 'message' => $msg, 'number' => $countNumber ]);
					}
				}
			}
		} else {
			$msg = trans('home.PLEASE_LOGIN_OR_SIGNUP_FOR_USE');
			return json_encode([ 'message' => $msg ]);
		}
	}

	public function get_subscribe($name, $string_Id) {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$getchannel = VideoModel::select('video.id', 'video.string_Id', 'video.channel_Id', 'channel.id', 'channel.title_name as channelname')
							->where('video.String_Id', '=', $string_Id)
							->join('channel', 'video.channel_Id', '=', 'channel.id')
							->first();

			if ($getchannel != NULL) {

				$checkchannel = ChanneSubscriberModel::where('channel_Id', '=', $getchannel->channel_Id)->first();

				if (count($checkchannel) > 0) {

					$memberid = explode(",", $checkchannel->member_Id);
					//var_dump($memberid);die;
					if (in_array($user->id, $memberid)) {
						$msg = trans('home.ALREADY_SUBSCRIBED_CHANNEL') . ' ' . $getchannel->channelname . '';
						return $msg;
					} else {
						$update_subscriber = ChanneSubscriberModel::where('channel_Id', '=', $checkchannel->channel_Id)
										->update(array('member_Id' => $checkchannel->member_Id . ',' . $user->id));
						if ($update_subscriber) {
							$msg = trans('home.THANKS_FOR_SUBSCRIBED') . ' ' . $getchannel->channelname . '';
							return $msg;
						}
					}
				} else {
					$insert_subscriber = new ChanneSubscriberModel();
					$insert_subscriber->channel_Id = $getchannel->channel_Id;
					$insert_subscriber->member_Id = $user->id;
					if ($insert_subscriber->save()) {
						$msg = trans('home.THANKS_FOR_SUBSCRIBED') . ' ' . $getchannel->channelname . '';
						return $msg;
					}
				}
			} else {
				$msg = trans('home.CHANNEL_NOT_FOUND');
				return $msg;
			}
		} else {
			$msg = trans('home.PLEASE_LOGIN_OR_SIGNUP_FOR_USE');
			return $msg;
		}
	}

	public function channelrelated($channel_Id) {
		$channel = DB::select(DB::raw("SELECT
			COUNT(table_video.id) AS totalvideo,
			table_channel.id,
				table_channel.title_name,
				table_channel.post_name,
				table_channel.poster,
				table_video.string_Id,
				table_video.channel_Id,
				table_video.video_src
				FROM table_video, table_channel
				WHERE table_video.status = ?
				and table_channel.id <> ?
				and table_video.channel_Id=table_channel.id
				GROUP BY table_video.channel_Id LIMIT 20"))
		->setBindings([VideoModel::STATUS_COMPLETED, $channel_Id]);
		return $channel;
	}

	public function videorelated($string_Id) {
		$video = VideoModel::where('status', '=', VideoModel::STATUS_COMPLETED)->where('String_Id', '<>', $string_Id)->take(20)->get();
		return $video;
	}

	public function post_comment(Request $req) {
		$timeService = new TimeService();

		if (\Session::has('User')) {
			if (\Request::Ajax()) {
				$user = \Session::get('User');
				$comment = $req->comment_text;
				$videoid = $req->id;
				$memberid = $user->user_id;
				$addcomment = new VideoCommentModel();
				$addcomment->video_Id = $videoid;
				$addcomment->member_Id = $memberid;
				$addcomment->post_comment = $comment;
				$addcomment->created_at = $timeService->dateWithTimeZone(date('Y-m-d H:i:s'));
				$addcomment->save();

				$html = '<li class="box">
							<img src="' . URL('public/upload/member/' . $user->avatar . '') . '" class="com_img">
							<span class="com_name">' . $user->firstname . " " . $user->lastname . '</span>
							<span class="com_date"><abbr class="timeago" title="' . date('Y-m-d h:i:s') . '">0 minute</abbr></span>
							<br />
							' . $comment . '
						</li>';
				return $html;
			}
		} else {
			return response()->json([
									'message' => trans('home.PLEASE_LOGIN_TO_COMMENT'),
									'status' => 401
											], 401);
		}
	}

	public function get_comment($string_Id) {
		$getcomment = VideoCommentModel::select('video_comment.*', 'members.firstname', 'members.lastname', 'members.avatar', 'members.user_id')
						->where('video_Id', '=', $string_Id)
						->where('comment_parent', 0)
						->join('members', 'video_comment.member_Id', '=', 'members.user_id')
						->orderby('video_comment.id', 'DESC')
						->paginate(4);

		return $getcomment;
	}

	public function get_countcomment($string_Id) {
		$countcomment = VideoCommentModel::where('video_Id', '=', $string_Id)->count();
		return $countcomment;
	}

	public function loadmore($string_Id) {
		$loadmore = VideoCommentModel::select('video_comment.*', 'members.firstname', 'members.lastname', 'members.avatar')
						->where('video_Id', '=', $string_Id)
						->where('comment_parent', 0)
						->join('members', 'video_comment.member_Id', '=', 'members.user_id')
						->orderby('video_comment.id', 'DESC')
						->paginate(4);
		$html = view('video.loadmore')->with('loadmore', $loadmore)->render();


		return response()->json([
			'status'        => 'success',
			'commentObject' => $loadmore->toJson(),
			'html'          => $html
		]);
	}

	public function get_search_video(Request $str) {

		if ($str->keyword != NULL && $str->keyword != "") {
			$keyword = $str->keyword;
			$getvideo = VideoModel::where(function($query) use ($keyword) {
								$query->where('status', '=', VideoModel::STATUS_COMPLETED);
								$query->where('post_name', 'LIKE', '%' . str_slug($keyword) . '%');
								$query->Orwhere('tag', 'LIKE', '%' . $keyword . '%');
							})
							->orderby('title_name', 'DESC')
							->paginate(24);
			$getvideo->appends(array('keyword' => $str->keyword));
			return view('search.search')->with('video', $getvideo)->with('keyword', $str->keyword)->with('categories', $this->get_categories());
		} else {
			return redirect('/');
		}
	}

	public function get_rating($vote, $string_id) {
		if (\Session::has('User')) {
			$user = \Session::get('User');

			$checkmember_voting = RatingModel::where('string_Id', '=', $string_id)->where('user_id', '=', $user->user_id)->first();
			if ($checkmember_voting != NULL) {
				$like = RatingModel::get_vote_like($string_id);
				$dislike = RatingModel::get_vote_dislike($string_id);
				$data = array(
					'like' => $like,
					'dislike' => $dislike,
					'msg' => trans('home.ALREADY_VOTED')
				);
				return $data;
			} else {

				if ($vote == "like") {
					$addvote = new RatingModel();
					$addvote->string_id = $string_id;
					$addvote->user_id = $user->user_id;
					$addvote->like = 1;
					if ($addvote->save()) {
						$like = RatingModel::get_vote_like($string_id);
						$dislike = RatingModel::get_vote_dislike($string_id);
						$total = $like + $dislike;
						$percent_like = ($like * 100) / $total;
						$percent_dislike = ($dislike * 100) / $total;
						$data = array(
							'like'            => $like,
							'dislike'         => $dislike,
							'percent_like'    => $percent_like,
							'percent_dislike' => $percent_dislike,
							'msg'             => ''
						);

						RatingModel::update_rating($string_id, $percent_like);
						return $data;
					}
				} else {
					$addvote = new RatingModel();
					$addvote->string_id = $string_id;
					$addvote->user_id = $user->user_id;
					$addvote->dislike = 1;
					if ($addvote->save()) {
						$like = RatingModel::get_vote_like($string_id);
						$dislike = RatingModel::get_vote_dislike($string_id);

						$total = $like + $dislike;
						$percent_like = ($like * 100) / $total;
						$percent_dislike = ($dislike * 100) / $total;
						$data = array(
							'like'            => $like,
							'dislike'         => $dislike,
							'percent_like'    => $percent_like,
							'percent_dislike' => $percent_dislike,
							'msg'             => ''
						);

						RatingModel::update_rating($string_id, $percent_like);
						return $data;
					}
				}
			}
		} else {
			$like = RatingModel::get_vote_like($string_id);
			$dislike = RatingModel::get_vote_dislike($string_id);
			$data = array(
				'like'    => $like,
				'dislike' => $dislike,
				'msg'     => '<a data-toggle="modal" data-target="#myModal" style="color:#ee577c" href="#">' . trans('home.LOGIN_TO_VOTE') . '</a>'
			);
			return $data;
		}
	}

	public function get_video_upload_temp($action) {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			if ($action == "upload") {
				echo 1;
			}if ($action == "get_temp") {
				$categories = $this->get_categories();
				$html = '<form class="form-horizontal result-upload" action="' . URL(getLang() . 'upload-video.html&action=upload') . '" name="share_video_form" enctype="multipart/form-data" method="post"  style="padding:10px;" >';
				//title input
				$html.='<div class="form-group">';
				$html.='<label style="margin:0px !important;padding-top:0 !important" for="share_from" class="col-lg-3 control-label">' . trans('home.VIDEO_TITLE') . '</label>';
				$html.='<div class="col-lg-9">';
				$html.='<input name="title_name" type="text" class="form-control" value="" id="title_name" placeholder="' . trans('home.VIDEO_TITLE') . '">';
				$html.='<div id="title_from_error" class="text-danger m-t-5" style="display: none;"></div></div></div>';
				//tag input
				$html.='<div class="form-group">';
				$html.='<label style="margin:0px !important;padding-top:0 !important" for="share_from" class="col-lg-3 control-label">' . trans('home.TAGS') . '</label>';
				$html.='<div class="col-lg-9">';
				$html.='<input name="tag" type="text" class="form-control" value="" id="tag" placeholder="' . trans('home.TAGS') . '">';
				$html.='<div id="title_from_error" class="text-danger m-t-5" style="display: none;"></div>
										 </div>
								</div>';
				//categories input
				$html.='<div class="form-group">';
				$html.='<label style="margin:0px !important;padding-top:0 !important" for="share_from" class="col-lg-3 control-label">' . trans('home.CATEGORY') . '</label>';
				$html.='<div class="col-lg-9">';
				$html.='<select id="categories_Id" multiple="multiple" name="post_result_cat[]" class="form-control">';
				foreach ($categories as $result) {
					$html.='<option data-name="' . $result->title_name . '" value="' . $result->id . '_' . $result->title_name . '" >' . $result->title_name . '</option>';
				}
				$html.='</select>';

				$html.='<div id="title_from_error" class="text-danger m-t-5" style="display: none;"></div>
										 </div>
								</div>';
				$html.='<div class="form-group">
							<label class="col-lg-3 control-label"></label>
							<div class="col-lg-9">

							</div>
						</div>';
				$html.='<script type="text/javascript">$("#categories_Id").select2();</script>';
				//input file
				$html.='<div class="form-group">
							<label style="margin:0px !important;padding-top:0 !important" for="share_message" class="col-lg-3 control-label">' . trans('home.SELECT_VIDEO_TO_UPLOAD') . '</label>
							<div class="col-lg-9">
								<div id="fileuploader"></div>
							</div>
						</div>';

				$html.='<script type="text/javascript">$("#fileuploader").uploadFile({url:"' . URL(getLang() . 'member-auto-upload-video') . '",fileName:"myfile",allowedTypes:"mp4,mov,avi,flv", formData: [{ name: "_token", value: $("meta[name=csrf-token]").attr("content") },{ name: "string_id", value: $("input[name=string]").attr("value") }],multiple: false,autoSubmit:true,onSuccess:function(files,data,xhr){$("#fileupload").val(files);}});</script>';
				if ($user->is_channel_member == 1) {
					$html.='<div class="form-group">';
					$html.='<label style="margin:0px !important;padding-top:0 !important" for="share_from" class="col-lg-3 control-label">' . trans('home.ONLY_SUBSCRIPTION') . '</label>';
					$html.='<div class="col-lg-9">';
					$html.='<input name="is_subscription" type="checkbox" >';
					$html.='</div></div>';
				}
				//option
				//submit
				$html.='<div class="form-group">
							<div class="col-md-3"></div>
							<div class="col-md-9">
								<input type="hidden" name="fileupload" id="fileupload">
								<input type="hidden" name="string" id="string" value="' . mt_rand() . '">
								<input type="hidden" name="_token" value="' . csrf_token() . '">
								<center><input name="submit_share" type="submit" value="' . trans('home.UPLOAD') . '" id="member-upload" class="btn btn-primary"></center>
							</div>
						</div>';
				$html.='</form>';
				return $html;
			}
		} else {
			return 0;
		}
	}

	public function member_auto_upload_video() {
		try {
			$conversion_config = ConversionModel::get_config();
			$date = date('Y-m-d');
			$folder = "".$_SERVER['DOCUMENT_ROOT']."/videos/";
			if(!is_dir($folder)) {
				$folder = mkdir("".$_SERVER['DOCUMENT_ROOT']."/videos/" , 0777, true);
				$upload_folder = "".$_SERVER['DOCUMENT_ROOT']."/videos/";
			} else {
				$upload_folder = "".$_SERVER['DOCUMENT_ROOT']."/videos/";
			}
			Log::info('Upload DOCUMENT_ROOT: ' . $_SERVER['DOCUMENT_ROOT']);

			if(isset($_FILES["myfile"])) {
				$ret = array();
				$string_id = $_POST['string_id'];
				$error = $_FILES["myfile"]["error"];
				Log::info('Upload myfile: ' . json_encode($_FILES["myfile"]));

				if(!$error && !is_array($_FILES["myfile"]["name"])) {
					$file_info = $_FILES["myfile"]["type"];
					$get_extend = '';
					if (in_array( $file_info,['video/flv', 'video/x-flv', 'application/octet-stream'])) {
						$get_extend = 'flv';
					} else {
						$extend = explode("/", $file_info);
						$get_extend = end($extend);
					}

					if($get_extend != 'mp4' && $get_extend != 'mov' && $get_extend != 'avi' && $get_extend != 'flv') {
						throw new Exception("Video file format not allow", 1);
					}

					Log::info('Upload myfile type: ' . $file_info);
					$fileName = "".$string_id.".".$get_extend."";
					$upload_video =  move_uploaded_file($_FILES["myfile"]["tmp_name"], $upload_folder.$fileName);
					Log::info('Upload myfile: ' . $upload_video);
					$ret[] = $fileName;
				} else {
					throw new Exception("Got some errors!", 1);
				}
			} else {
				throw new Exception("Not exist myfile!", 1);
			}
		} catch (\Exception $e) {
			Log::error('Upload ERR: ' . $e->getMessage());
			return response()->json(["error" => $e->getMessage()]);
		}

		return response()->json(["status" => "ok"]);
	}

	public function post_member_video_upload(Request $get) {
		$conversion_config = ConversionModel::get_config();
		if ($get) {
			if (\Session::has('User')) {
				if ($get->post_result_cat != NULL) {
					$tostring = implode(',', $get->post_result_cat);
				}
				$string = $get->string;
				$user = \Session::get('User');
				$title_name = $get->title_name;
				$tag = $get->tag;
				$user_id = $user->user_id;
				$categories = isset($tostring) ? $tostring : '';
				$cat_id = isset($tostring) ? cat_form_array($tostring) : '';
				$fileupload = $get->fileupload;
				if (empty($fileupload)) {
					return redirect(getLang() . 'member-proflie.html?action=upload')->with('msg', trans('home.SELECT_VIDEO_TO_UPLOAD'));
				}
				$addvideo = new VideoModel();
				$timeService = new TimeService();
				// dd($timeService->dateWithTimeZone(date('Y-m-d H:i:s')));
				$now = $timeService->dateWithTimeZone(date('Y-m-d H:i:s'));
				// dd(time($now));

				$extend                    = explode(".", $fileupload);
				$get_extend                = strtolower(end($extend));
				$addvideo->title_name      = $title_name;
				$addvideo->upTime          = strtotime($now);
				$addvideo->website         = "upload";
				$addvideo->extension       = $get_extend;
				$addvideo->user_id         = $user_id;
				$addvideo->categories_Id   = $categories;
				$addvideo->cat_id          = $cat_id;
				$addvideo->tag             = $tag;
				$addvideo->is_subscription = isset($get->is_subscription) ? 1 : 0;
				$addvideo->post_name       = str_slug($title_name, '-');
				$addvideo->string_Id       = $string;
				$addvideo->status          = VideoModel::CONVERT_STATUS;
				$addvideo->created_at      = $now;
				$addvideo->updated_at      = $now;

				// check exist channel
				$channel = ChannelModel::where('user_id', '=', "$user_id")->first();
				if (!empty($channel)) {
					$addvideo->channel_Id = $channel->id;
				}

				if ($addvideo->save()) {
					if (isset($tostring)) {
						$cat_post = cat_array($tostring);
						for ($i = 0; $i < count($get); $i++) {
							$videocat = new VideoCatModel();
							$videocat->video_id = $get->string;
							$videocat->cat_id = $cat_post[$i];
							$videocat->save();
						}
					}

					//send mail-subscription
					return redirect(getLang() . 'member-proflie.html?action=upload')->with('msg', trans('home.UPLOAD_SUCCESSFULLY'));
				}
				return redirect(getLang() . 'member-proflie.html?action=upload')->with('msg', trans('home.UPLOAD_FAILED'));
			}
			return redirect(getLang() . 'login.html');
		}
	}

	public function send_mail_new_video($video_id, $user_id) {

		$is_subscription = VideoModel::where('id', '=', $video_id)
										->where('user_id', '=', $user_id)
										->where('is_subscription', '=', 1)->first();
		if ($is_subscription != NULL) {
			$get_channel = ChannelModel::where('id', '=', $is_subscription->channel_Id)->first();
			if ($get_channel != NULL) {
				$get_channel_subscription = SubsriptionModel::where('channel_id', '=', $is_subscription->channel_Id)->where('status', '=', VideoModel::STATUS_COMPLETED)->get();
				$config = OptionModel::get_config();
				if (count($get_channel_subscription) > 0) {
					foreach ($get_channel_subscription as $result) {
						$rating = \App\Models\RatingModel::get_percent($is_subscription->string_Id);
						$get_member_name = MemberModel::where('user_id', '=', $result->user_id)->first();
						$sendmail = Mail::send('admincp.mail.channel_send_mail_new_video', array(
												'firstname'      => $get_member_name->firstname,
												'lastname'       => $get_member_name->lastname,
												'video_id'       => $is_subscription->string_Id,
												'video_thumb'    => $is_subscription->poster,
												'video_duration' => $is_subscription->duration,
												'rating'         => floor($rating['percent_like']),
												'video_name'     => $is_subscription->title_name,
												'channel_name'   => $get_channel->title_name,
												'site_name'      => $config->site_name
														), function($message) use($result) {
											$message->to($result->email)->subject('Channel New Video Subscription !');
										});
						if ($sendmail) {
							return redirect(getLang() . 'member-proflie.html?action=upload')->with('msg', trans('home.UPLOAD_SUCCESSFULLY'));
						}
					}
				}
			}
		}
	}

	public function get_video_member_upload() {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$video = VideoModel::where('user_id', '=', $user->user_id)->paginate(10);
			$html = "";
			$html.='<div class="row">';
			foreach ($video as $result) {
				$html.='<div class="col-sm-6 col-sm-3 image-left">
							<div class="col">
								<div class="col_img">
									<a id="edit-video" data-toggle="tooltip" data-placement="right" title="Delete video" class="edit-video" data-id="' . $result->string_Id . '" href="javascript:void(0);"><span class="fa fa-trash-o"></span></a>
									<span class="hd">HD</span>
									<a href="' . URL(getLang() . 'watch') . '/' . $result->string_Id . '/' . $result->post_name . '.html">';
				if ($result->poster == "") {
					$html.='<img src="' . URL('public/assets/images/no-image.jpg') . '" alt="' . $result->title_name . '"  />';
				} else {
					$html.='<img src="' . $result->poster . '" alt="' . $result->title_name . '" style="max-width: 100% !important" height="177" />';
				}
				$html.=' </a>';
				$html.='<div class="position_text">
										<p class="icon-like"></p>
										<p class="percent">90%</p>
										<p class="time_minimute">' . sec2hms($result->duration) . '</p>
									</div>
								</div>
								<h3><a href="' . URL(getLang() . 'watch') . '/' . $result->string_Id . '/' . $result->post_name . '.html">' . str_limit($result->title_name, 20) . '</a></h3>
							</div>
						</div> ';
			}
			$html.='</div><div class="clearfix"></div>';
			$html.='<div id="edit-video-upload"></div>';
			return $html;
		} else {
			return 0;
		}
	}

	public function get_member_delete_video($string) {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$check_video = VideoModel::where('string_Id', '=', $string)->where('user_id', '=', $user->user_id)->first();
			if ($check_video != NULL) {
				$delete = VideoModel::where('string_Id', '=', $string)->where('user_id', '=', $user->user_id)->delete();
				if ($delete) {
					return 1;
				} else {
					return 2;
				}
			} else {
				return 0;
			}
		} else {
			return 3;
		}
	}

	public function get_member_edit_thumbnail($string) {
		$conversion_config = ConversionModel::get_config();

		if (\Session::has('User')) {
			$user = \Session::get('User');
			$check_video = VideoModel::where('string_Id', '=', $string)->where('user_id', '=', $user->user_id)->first();
			if ($check_video != NULL) {
				$thumbnail = base_path() . "/videos/" . date_format($check_video->created_at, 'Y-m-d') . "/" . $string . "";
				$site_path = URL() . "/videos/" . date_format($check_video->created_at, 'Y-m-d') . "/" . $string . "";
				$video_path = getLang() . "/videos/" . date_format($check_video->created_at, 'Y-m-d') . "/" . $string . ".mp4";
				if (file_exists($video_path) == true) {
					$frame = array(10, 20, 30, 40);
					for ($i = 0; $i < count($frame); $i++) {

						$conver_thumb = shell_exec("" . $conversion_config->ffmpeg_path . " -i " . $video_path . " -deinterlace -an -ss " . $frame[$i] . " -f mjpeg -t 1 -r 1 -y -s 640x360 " . $thumbnail . "_" . $frame[$i] . ".jpg 2>&1");
					}
					$html = '';
					$html.='<h3>Select thumbnail: Video ' . str_limit($check_video->title_name, 50) . ' </h3>';
					$html.='<div id="show-thumb" class="row">';
					for ($i = 0; $i < count($frame); $i++) {
						$html.='<div class="col-sm-6 col-sm-3 image-left" ><div class="col"><div class="col_img">';
						$html.='<span id="chose_complete_' . $frame[$i] . '" style="display:none;" ><i class=" fa fa-check"></i></span>';
						$html.='<img src="' . $site_path . '_' . $frame[$i] . '.jpg"  class="chose_thumb pointer" data-id="' . $string . '_' . $frame[$i] . '">';
						$html.='</div></div></div> ';
					}
					$html.='</div><div class="clearfix"></div><div class="pull-right" style="margin-top:15px;"><button class="btn btn-signup" id="save-thumb" style="display:none">Save</button></div>';
					return $html;
				} else {
					return 2;
				}
			} else {
				return 0;
			}
		} else {
			return 3;
		}
	}

	public function get_save_thumb_chose($data) {
		if (\Session::has('User')) {
			if ($data) {
				$split = explode('_', $data);
				$video_id = $split[0];
				$thumb_id = $split[1];
				$check_video = VideoModel::where('string_Id', '=', $video_id)->first();
				if ($check_video != NULL) {
					$new_path_thumb = "" . URL() . "/videos/" . date_format($check_video->created_at, 'Y-m-d') . "/" . $data . ".jpg";
					$update = VideoModel::where('string_Id', '=', $video_id)->update(array('poster' => $new_path_thumb));
					if ($update) {
						return 1;
					}
				} else {
					return 4;
				}
			} else {
				return 3;
			}
		} else {
			return 2;
		}
	}

	public function show_static_page($id) {
		if ($id != NULL) {
			$checkid = StaticPageModel::find($id);
			if (!empty($checkid)) {
				return view('static.index')->with('static', $checkid);
			} else {
				return redirect('')->with('msg', 'Page not found!');
			}
		} else {
			return redirect('')->with('msg', 'Page not found!');
		}
	}

	public function get_action_video($action = NULL, $catid = NULL) {
		if ($action != NULL) {
			switch ($action) {
				case 'all':
					return $this->get_all_video();
					break;
				case 'cat':
					return $this->get_video_cat($catid);
					break;
				case 'new' :
					return $this->get_new_video($action);
					break;
				case 'top-rated':
					return $this->get_video_top_rate();
					break;
				case 'most-view':
					return $this->get_video_most_view();
					break;
				case 'most-favorited':
					return $this->get_video_most_favorite($action);
					break;
				case 'most-commented':
					return $this->get_video_most_commented($action);
				default:
					return $this->get_video_cat();
					break;
			}
		} else {
			return redirect('');
		}
	}

	public function get_all_video() {

		$video = VideoModel::where('status', '=', VideoModel::STATUS_COMPLETED)->paginate(perPage());
		return view('video.video')->with('video', $video)->with('title', trans('home.ALL_VIDEO'))->with('title_xs', trans('home.ALL_VIDEO'));
	}

	public function get_new_video($action) {
		$video = VideoModel::where('status', '=', VideoModel::STATUS_COMPLETED)->Orderby('created_at', 'DESC')->paginate(perPage());
		return view('video.video')->with('video', $video)->with('title', trans('home.NEWEST_VIDEOS'))->with('action', $action)->with('title_xs', trans('home.NEWEST_VIDEOS'));
	}

	public function get_video_top_rate() {
		$video = VideoModel::where('status', '=', VideoModel::STATUS_COMPLETED)->Orderby('rating', 'DESC')->paginate(perPage());
		return view('video.video')->with('video', $video)->with('title', trans('home.TOP_RATE_VIDEOS'));
	}

	public function get_video_most_view() {
		$video = VideoModel::where('status', '=', VideoModel::STATUS_COMPLETED)->Orderby('total_view', 'DESC')->paginate(perPage());
		return view('video.video')->with('video', $video)->with('title', trans('home.MOST_VIEWED_VIDEO'))->with('title_xs', trans('home.VIEWS'));
	}

	public function get_video_most_favorite($action) {
		$get_favorite = MemberVideoModel::get();
		$list_temp = array();
		for ($i = 0; $i < count($get_favorite); $i++) {
			if (!in_array($get_favorite[$i]->video_Id, $list_temp)) {
				array_push($list_temp, $get_favorite[$i]->video_Id);
			}
		}
		$new_array = implode(',', $list_temp);
		$array = explode(',', $new_array);
		$listvideo_temp = array_unique($array);
		$new_list = implode(',', $listvideo_temp);
		$video = VideoModel::where('status', '=', VideoModel::STATUS_COMPLETED)->whereIn('string_Id', $listvideo_temp)->Orderby('title_name', 'DESC')->paginate(perPage());
		return view('video.video')->with('video', $video)->with('title', trans('home.MOST_FAVORITED_VIDEO'))->with('title_xs', trans('home.FAVORITED'))->with('action', $action);
	}

	public function get_video_most_commented($action) {
		$get_comment = VideoCommentModel::groupby('video_Id')->get();
		$list_comment_array = array();
		for ($i = 0; $i < count($get_comment); $i++) {
			array_push($list_comment_array, $get_comment[$i]->video_Id);
		}
		$video = VideoModel::where('status', '=', VideoModel::STATUS_COMPLETED)->whereIn('string_Id', $list_comment_array)->Orderby('title_name', 'DESC')->paginate(perPage());
		return view('video.video')->with('video', $video)->with('title', trans('home.MOST_COMMENTED_VIDEO'))->with('title_xs', trans('home.COMMENTED'))->with('action', $action);
	}

	public function get_video_cat($catid) {

		$check_catid = CategoriesModel::find($catid);
		if ($check_catid != NULL) {
			$video_cat = $check_catid->video_cat($catid);

			return view('video.video_cat')->with('video_cat', $video_cat)->with('catname', $check_catid->title_name)->with('title_xs', '');
		} else {

			return redirect(getLang() . 'video.html&action=all')->with('msg', trans('home.VIDEO_NOT_FOUND_FORM_CATEGORIES'));
		}
	}

	public function get_action_filter($action, $date, $time) {
		if (\Request::ajax()) {
			switch ($action) {
				case 'new':
					return $this->get_filter_newest_video($action, $date, $time);
					break;
				case 'most-favorited':
					return $this->get_filter_favorited_video($action, $date, $time);
					break;
				case 'most-commented':
					return $this->get_filter_commented_video($action, $date, $time);
					break;
			}
		} else {
			return redirect(getLang() . 'video.html&action=' . $action . '');
		}
	}

	public function get_filter_newest_video($action, $date, $time) {
		//var_dump($country."/".$time);
		$compare = "=";
		if ($date == 'today') {

			// $time="all";
		}
		if ($time == 'all') {
			$fist = 0;
			$end = 7200;
			$time = "";
			$time_name = trans('home.ALL_TIMES');
		}
		if ($time == "1-3") {
			$fist = 1;
			$end = 180;
			$time = "1-3";
			$time_name = trans('home.VIDEOS') . " (1-3min)";
		}
		if ($time == "3-10") {
			$fist = 181;
			$end = 600;
			$time = "3-10";
			$time_name = trans('home.VIDEOS') . " (3-10min)";
		}
		if ($time == "10+") {
			$fist = 601;
			$end = 7200;
			$time = "10+";
			$time_name = trans('home.VIDEOS') . " (10+min)";
		}
		if ($date != "today") {
			if ($date == "week") {
				$lastweek = date_create('Sunday last week');
				$thisweek = date_create('Sunday this week');
				$video = VideoModel::whereRaw("updated_at BETWEEN '" . get_object_vars($lastweek)['date'] . "' and '" . get_object_vars($thisweek)['date'] . "'  and duration BETWEEN " . $fist . " and " . $end . "")
								->orderby('created_at', 'DESC')
								->paginate(perPage());
			}if ($date == "month") {
				$video = VideoModel::whereRaw("duration BETWEEN " . $fist . " and " . $end . "")
								->where(DB::raw('MONTH(updated_at)'), '=', date('n'))
								->orderby('created_at', 'DESC')
								->paginate(perPage());
			}if ($date == "all") {
				$video = VideoModel::whereRaw("duration BETWEEN " . $fist . " and " . $end . "")
												->where('status', '=', VideoModel::STATUS_COMPLETED)
												->orderby('created_at', 'DESC')->paginate(perPage());
			}
		} else {
			$video = VideoModel::whereBetween('duration', array($fist, $end))
							->where('created_at', 'like', '' . date('Y-m-d') . '%')
							->orderby('created_at', 'DESC')
							->groupBy('id')->paginate(perPage());
		}


		if ($video) {
			return view('video.video_filter')->with('video', $video)->with('date', $date)->with('time', $time_name)->with('data_time', $time);
		}
	}

	public function get_filter_favorited_video($action, $date, $time) {
		//var_dump($country."/".$time);
		$get_favorite = MemberVideoModel::get();
		$list_temp = array();
		for ($i = 0; $i < count($get_favorite); $i++) {
			if (!in_array($get_favorite[$i]->video_Id, $list_temp)) {
				array_push($list_temp, $get_favorite[$i]->video_Id);
			}
		}
		$new_array = implode(',', $list_temp);
		$array = explode(',', $new_array);
		$listvideo_temp = array_unique($array);
		$new_list = implode(',', $listvideo_temp);

		$compare = "=";
		if ($date == 'today') {

			// $time="all";
		}
		if ($time == 'all') {
			$fist = 0;
			$end = 7200;
			$time = "";
			$time_name = trans('home.ALL_TIMES');
		}
		if ($time == "1-3") {
			$fist = 1;
			$end = 180;
			$time = "1-3";
			$time_name = trans('home.VIDEOS') . " (1-3min)";
		}
		if ($time == "3-10") {
			$fist = 181;
			$end = 600;
			$time = "3-10";
			$time_name = trans('home.VIDEOS') . " (3-10min)";
		}
		if ($time == "10+") {
			$fist = 601;
			$end = 7200;
			$time = "10+";
			$time_name = trans('home.VIDEOS') . " (10+min)";
		}
		if ($date != "today") {
			if ($date == "week") {

				$lastweek = date_create('Sunday last week');
				$thisweek = date_create('Sunday this week');
				$video = VideoModel::whereRaw("updated_at BETWEEN '" . get_object_vars($lastweek)['date'] . "' and '" . get_object_vars($thisweek)['date'] . "'  and duration BETWEEN " . $fist . " and " . $end . "")
								->where('status', '=', VideoModel::STATUS_COMPLETED)
								->whereIn('string_Id', $listvideo_temp)
								->orderby('created_at', 'DESC')
								->paginate(perPage());
			}if ($date == "month") {
				$video = VideoModel::whereRaw("duration BETWEEN " . $fist . " and " . $end . "")
								->where(DB::raw('MONTH(updated_at)'), '=', date('n'))
								->where('status', '=', VideoModel::STATUS_COMPLETED)
								->whereIn('string_Id', $listvideo_temp)
								->orderby('created_at', 'DESC')
								->paginate(perPage());
			}if ($date == "all") {
				$video = VideoModel::whereRaw("duration BETWEEN " . $fist . " and " . $end . "")
												->where('status', '=', VideoModel::STATUS_COMPLETED)
												->whereIn('string_Id', $listvideo_temp)
												->orderby('created_at', 'DESC')->paginate(perPage());
			}
		} else {

			$video = VideoModel::whereBetween('duration', array($fist, $end))
											->whereIn('string_Id', $listvideo_temp)
											->where('created_at', 'like', '' . date('Y-m-d') . '%')
											->where('status', '=', VideoModel::STATUS_COMPLETED)
											->orderby('created_at', 'DESC')
											->groupBy('id')->paginate(perPage());
		}


		if ($video) {
			return view('video.video_filter')->with('video', $video)->with('date', $date)->with('time', $time_name)->with('data_time', $time);
		}
	}

	public function get_filter_commented_video($action, $date, $time) {
		//var_dump($country."/".$time);
		$get_comment = VideoCommentModel::Groupby('video_Id')->get();
		$list_comment_array = array();
		for ($i = 0; $i < count($get_comment); $i++) {
			array_push($list_comment_array, $get_comment[$i]->video_Id);
		}
		$compare = "=";
		if ($date == 'today') {

			// $time="all";
		}
		if ($time == 'all') {
			$fist = 0;
			$end = 7200;
			$time = "";
			$time_name = trans('home.ALL_TIMES');
		}
		if ($time == "1-3") {
			$fist = 1;
			$end = 180;
			$time = "1-3";
			$time_name = trans('home.VIDEOS') . " (1-3min)";
		}
		if ($time == "3-10") {
			$fist = 181;
			$end = 600;
			$time = "3-10";
			$time_name = trans('home.VIDEOS') . " (3-10min)";
		}
		if ($time == "10+") {
			$fist = 601;
			$end = 7200;
			$time = "10+";
			$time_name = trans('home.VIDEOS') . " (10+min)";
		}
		if ($date != "today") {
			if ($date == "week") {
				$lastweek = date_create('Sunday last week');
				$thisweek = date_create('Sunday this week');
				$video = VideoModel::whereRaw("updated_at BETWEEN '" . get_object_vars($lastweek)['date'] . "' and '" . get_object_vars($thisweek)['date'] . "'  and duration BETWEEN " . $fist . " and " . $end . "")
								->whereIn('string_Id', $list_comment_array)
								->where('status', '=', VideoModel::STATUS_COMPLETED)
								->orderby('created_at', 'DESC')
								->paginate(perPage());
			}if ($date == "month") {
				$video = VideoModel::whereRaw("duration BETWEEN " . $fist . " and " . $end . "")
								->where(DB::raw('MONTH(updated_at)'), '=', date('n'))
								->whereIn('string_Id', $list_comment_array)
								->where('status', '=', VideoModel::STATUS_COMPLETED)
								->orderby('created_at', 'DESC')
								->paginate(perPage());
			}if ($date == "all") {
				$video = VideoModel::whereRaw("duration BETWEEN " . $fist . " and " . $end . "")
												->whereIn('string_Id', $list_comment_array)
												->where('status', '=', VideoModel::STATUS_COMPLETED)
												->orderby('created_at', 'DESC')->paginate(perPage());
			}
		} else {

			$video = VideoModel::whereBetween('duration', array($fist, $end))
											->where('created_at', 'like', '' . date('Y-m-d') . '%')
											->whereIn('string_Id', $list_comment_array)
											->where('status', '=', VideoModel::STATUS_COMPLETED)
											->orderby('created_at', 'DESC')
											->groupBy('id')->paginate(perPage());
		}


		if ($video) {
			return view('video.video_filter')->with('video', $video)->with('date', $date)->with('time', $time_name)->with('data_time', $time);
		}
	}

	public function sec2hms($sec, $padHours = false) {
		$hms = "";
		$hours = intval(intval($sec) / 3600);
		if ($hours > 0) {
			$hms .= ($padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT) . ':' : $hours . ':';
		}
		$minutes = intval(($sec / 60) % 60);
		$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT) . ':';
		$seconds = intval($sec % 60);
		$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
		return $hms;
	}

	public function get_tag($string_id) {
		$get_tag = VideoHelper::getVideoDetail($string_id);
		if ($get_tag != NULL) {
			$tag_array = explode(',', $get_tag->tag);
			return $tag_array;
		}

		return [];
	}

	//get Author function upload video
	public function get_video_author_upload($stringId) {
		return VideoHelper::getAuthor($stringId);
	}

//end function
	//function get_member_link
	public function get_link_member($string_id) {
		if (\Session::has('User')) {
			$user = \Session::get('User');

			return VideoHelper::getAuthorLink($string_id, $user->user_id);
		}

		return null;
	}

//end function

	public function check_subscribe($id) {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$user_id = $user->user_id;
			$checkchannel = ChanneSubscriberModel::where('channel_Id', '=', $id)->first();
			if (count($checkchannel)) {
				$users = explode(',', $checkchannel->member_Id);
				if (in_array($user_id, $users)) {
					return trans('home.SUBSCRIBED');
				} else {
					return trans('home.SUBSCRIBE');
				}
			} else {
				return trans('home.SUBSCRIBE');
			}
		} else {
			return trans('home.SUBSCRIBE');
		}
	}

	public function check_favorited($string_id) {
		// if (\Session::has('User')) {
		// 	$user = \Session::get('User');
		// 	$member_video = MemberVideoModel::where('member_Id', '=', $user->id)->first();
		// 	if ($member_video != NULL) {
		// 		$video_list = explode(',', $member_video->video_Id);
		// 		if (in_array($string_id, $video_list)) {
		// 			return trans('home.FAVORITED');
		// 		} else {
		// 			return trans('home.ADD_TO_MY_FAVORITE_VIDEOS');
		// 		}
		// 	} else {
		// 		return trans('home.ADD_TO_MY_FAVORITE_VIDEOS');
		// 	}
		// } else {
		// 	return trans('home.ADD_TO_MY_FAVORITE_VIDEOS');
		// }
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$member_video = MemberVideoModel::where('member_Id', '=', $user->user_id)->first();
			if ($member_video != NULL) {
				$video_list = explode(',', $member_video->video_Id);
				if (in_array($string_id, $video_list)) {
					return 'true';
				}
			}
		}
		return 'false';
	}

	public function count_favorited($string_id) {
		return MemberVideoModel::where('video_Id', 'LIKE', '%'. $string_id .'%')->count();
	}

	public function get_search_filter($keyword, $sort, $date, $time) {
		if (\Request::ajax()) {
			$fist = 0;
			$end = 7200;
			if ($sort == 'relevance') {
				$order = "title_name";
				$des = 'DESC';
			}
			if ($sort == 'uploaddate') {
				$order = "created_at";
				$des = 'DESC';
			}
			if ($sort == 'rating') {
				$order = 'rating';
				$des = 'DESC';
			}
			if ($sort == "mostviewed") {
				$order = 'total_view';
				$des = 'DESC';
			}

			if ($time == 'all') {
				$fist = 0;
				$end = 7200;
				$time = "";
				$time_name = trans('home.ALL_TIMES');
			}
			if ($time == "1-3") {
				$fist = 1;
				$end = 180;
				$time = "1-3";
				$time_name = trans('home.VIDEOS') . " (1-3min)";
			}
			if ($time == "3-10") {
				$fist = 181;
				$end = 600;
				$time = "3-10";
				$time_name = trans('home.VIDEOS') . " (3-10min)";
			}
			if ($time == "10+") {
				$fist = 601;
				$end = 7200;
				$time = "10+";
				$time_name = trans('home.VIDEOS') . " (10+min)";
			}

			if ($date == "week") {
				$lastweek = date_create('Sunday last week');
				$thisweek = date_create('Sunday this week');
				$search_result = VideoModel::whereRaw("updated_at BETWEEN '" . get_object_vars($lastweek)['date'] . "' and '" . get_object_vars($thisweek)['date'] . "'  and duration BETWEEN " . $fist . " and " . $end . " and  (post_name LIKE '%" . str_slug($keyword) . "%' or tag LIKE '%" . str_slug($keyword) . "%')")
								->where('status', '=', VideoModel::STATUS_COMPLETED)
								->orderby($order, $des)
								->paginate(perPage());
			}if ($date == "month") {
				$search_result = VideoModel::whereRaw("duration BETWEEN " . $fist . " and " . $end . " and  (post_name LIKE '%" . str_slug($keyword) . "%' or tag LIKE '%" . str_slug($keyword) . "%')")->where(DB::raw('MONTH(updated_at)'), '=', date('n'))
								->where('status', '=', VideoModel::STATUS_COMPLETED)
								->orderby($order, $des)
								->paginate(perPage());
			}if ($date == "today") {
				$search_result = VideoModel::whereRaw("duration BETWEEN " . $fist . " and " . $end . " and  (post_name LIKE '%" . str_slug($keyword) . "%' or tag LIKE '%" . str_slug($keyword) . "%') and created_at LIKE '%" . date('Y-m-d') . "%' ")
								->where('status', '=', VideoModel::STATUS_COMPLETED)
								->orderby($order, $des)
								->paginate(perPage());
			} else {
				$search_result = VideoModel::whereRaw("duration BETWEEN " . $fist . " and " . $end . " and  (post_name LIKE '%" . str_slug($keyword) . "%' or tag LIKE '%" . str_slug($keyword) . "%')")
								->where('status', '=', VideoModel::STATUS_COMPLETED)
								->orderby($order, $des)
								->paginate(perPage());
			}

			if ($search_result) {
				$count = count($search_result);
				return view('search.search-filter')->with('video', $search_result)->with('count_video', $count);
			}
		} else {
			return redirect(getLang() . 'search.html?keyword=' . $keyword . '');
		}
	}

	//get embed frame
	public function get_video_embed($id) {
		$check_video_id = VideoModel::where('string_Id', '=', $id)->first();
		if ($check_video_id != NULL) {
			return view('video.embedframe')->with('videovideo_embed', $check_video_id);
		} else {
			return with('video_error', trans('home.VIDEO_NOT_FOUND'));
		}
	}

	public function post_add_view() {
		$input = Input::only('videoId');
		$videoId = $input['videoId'];
		VideoModel::where('string_Id', '=', $videoId)->increment('total_view');
		// $video = VideoModel::where('string_Id', '=', $videoId)->first();
		// $video->total_view += 1;
		// $video->save();
		VideoHelper::removeDetailCache($videoId);
	}

}
