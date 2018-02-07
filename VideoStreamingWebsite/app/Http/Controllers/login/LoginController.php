<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\Modules\Modules;
use App\Models\ActivityLogModel;
use App\Models\MemberModel;
use App\Models\MemberVideoModel;
use App\Models\MemberMessageModel;
use App\Models\MemberFriendModel;
use App\Models\VideoModel;
use App\Models\SubsriptionModel;
use App\Models\ChannelModel;
use App\Models\MemberReportModel;
use App\Models\ContactModel;
use App\Models\FQAModel;
use App\Models\ChanneSubscriberModel;
use App\Models\CategoriesModel;
use App\Models\UserSignupModel;
use App\Models\UsersModel;
use App\Models\MSGPrivateModel;
use App\Models\ProfileCommentModel;
use App\Models\EmailSettingModel;
use App\Models\OptionModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Helper\AppHelper;

class LoginController extends Controller {

	/**
	 * Font end Login
	 * @param  Request $email & $password [description]
	 * @return [respone]           [description]
	 */
	public function signin(Request $Request) {

		$current_url = $Request->current_url;
		$user = MemberModel::where('email', '=', $Request->email)
						->where('status', '=', '1')
						->where('password', '=', MD5($Request->password))
						->first();
		if ($user) {
			\Session::put("User", $user);
			MemberModel::setUserOnline($Request->email);

			//set activity logs
			$log = new ActivityLogModel();
			$log->user_id = $user->id;
			$log->type = 'login';
			$log->object_id = $user->id;
			$log->description = 'Signin at ' . date('Y-m-d');
			$log->save();
			if ($user['roles'] == 0 && $user['is_profile_updated'] == 0) {
				return redirect($current_url); //profile
			}
			return redirect($current_url); //index
		}

		return view('login.loginpage')->with('msglogin', trans('home.EMAIL_OR_PASSWORD_NOT_MATCH'));
	}

	/**
	 * [logout description]
	 * @return [type] [description]
	 */
	public function logout() {
		if (\Session::has('User')) {
			$user = \Session::get("User");
			MemberModel::setUserOffOnline($user->email);

			//set activity logs
			$log = new ActivityLogModel();
			$log->user_id = $user->id;
			$log->type = 'logout';
			$log->object_id = $user->id;
			$log->description = 'Signout at ' . date('Y-m-d');
			$log->save();

			return redirect('/');
		} else {

			return redirect('/');
		}
	}

	/**
	 * [get_login description]
	 * @return [type] [description]
	 */
	public function get_login() {
		$categoris = CategoriesModel::where('status', '=', '1')
						->orderby('title_name', 'asc')
						->get();
		return view('login.loginpage')->with('categories', $categoris);
	}

	/**
	 * [get Member profile]
	 * @return [resources] [description]
	 */
	public function get_memberprofile() {
		$action = Input::get('action');

		$categoris = CategoriesModel::where('status', '=', '1')
						->orderby('title_name', 'asc')
						->get();
		if (\Session::has("User")) {
			$user = \Session::get("User");

			$member = MemberModel::where('id', '=', $user->id)->first();
			if ($member) {
				if ($action == "upload") {
					$categories = CategoriesModel::where('status', '=', '1')
									->orderby('title_name', 'asc')
									->get();
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
					$html.='<input name="tag" type="text" class="form-control" value="" id="tag" placeholder="video tags">';
					$html.='<div id="title_from_error" class="text-danger m-t-5" style="display: none;"></div>
										 </div>
								</div>';
					//categories input
					$html.='<div class="form-group">';
					$html.='<label style="margin:0px !important;padding-top:0 !important" for="share_from" class="col-lg-3 control-label">' . trans('home.CATEGORY') . '</label>';
					$html.='<div class="col-lg-9">';
					$html.='<select id="categories_Id" 1 multiple="multiple" name="post_result_cat[]" class="form-control">';
					foreach ($categories as $result) {
						$html.='<option data-name="' . $result->title_name . '" value="' . $result->ID . '_' . $result->title_name . '" >' . $result->title_name . '</option>';
					}
					$html.='</select>';

					$html.='<div id="title_from_error" class="text-danger m-t-5" style="display: none;"></div>
						</div></div>';
					//input file
					$html.='<div class="form-group">
								<label class="col-lg-3 control-label"></label>
								<div class="col-lg-9">
								</div>
							</div>';
					$html.='<script type="text/javascript">$(document).ready(function(){$("#categories_Id").select2(); })</script>';
					$html.='<div class="form-group">
								<label style="margin:0px !important;padding-top:0 !important" for="share_message" class="col-lg-3 control-label">' . trans('home.SELECT_VIDEO_TO_UPLOAD') . '</label>
								<div class="col-lg-9">
									<div id="fileuploader"></div>
								</div>
							</div>';

					$html.='<script type="text/javascript"> $(document).ready(function() {$("#fileuploader").uploadFile({url:"' . URL(getLang() . 'member-auto-upload-video') . '",fileName:"myfile",allowedTypes:"mp4,mov,avi,flv", formData: [{ name: "_token", value: $("meta[name=csrf-token]").attr("content") },{ name: "string_id", value: $("input[name=string]").attr("value") }],multiple: false,autoSubmit:true,onSuccess:function(files,data,xhr){$("#fileupload").val(files);}});});</script>';
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

					return view('login.profile')->with('$member', $member)->with('active', 'active')->with('upload_content', $html);
				}
				return view('login.profile')->with('$member', $member)->with('default', 'active');
			}
		}
		return view('login.loginpage');
	}

	/**
	 * [get member colection video description]
	 * @param  string $date    [description]
	 * @param  string $keyword [description]
	 * @return [type]          [description]
	 */
	public function get_membercollections($date = "", $keyword = "all") {
		if (\Session::has("User")) {
			if ($date != NULL and $keyword == "all") {
				$user = \Session::get("User");
				$videomember = MemberVideoModel::where('member_Id', '=', $user->user_id)->first();
				if ($videomember != NUll) {
					$videoid = explode(',', $videomember->video_Id);
					$video = VideoModel::whereIn('string_Id', $videoid)
									->where('created_at', 'like', '' . $date . '%')
									->paginate(perPage());

					if ($video) {
						return view('login.membervideo')->with('video', $video);
					}
				}
				return view('login.membervideo')->with('msgmember', trans('home.ADD_TO_MY_FAVORITE_VIDEOS'));
			}
			if ($date == "all" and $keyword != "all") {

				$user = \Session::get("User");
				$videomember = MemberVideoModel::where('member_Id', '=', $user->user_id)->first();
				if ($videomember != NUll) {
					$videoid = explode(',', $videomember->video_Id);
					$video = VideoModel::where(function($query)use($keyword, $date) {
										$query->where(function($query) use ($keyword) {
											$query->where('post_name', 'LIKE', '%' . str_slug($keyword) . '%');
											$query->Orwhere('tag', 'LIKE', '%' . $keyword . '%');
											$query->Orwhere('title_name', '=', '%' . $keyword . '%');
										});
									})
									->whereIn('string_Id', $videoid)
									->paginate(perPage());
					if ($video) {
						return view('login.membervideo')->with('video', $video);
					}
				}
				return view('login.membervideo')->with('msgmember', trans('home.ADD_TO_MY_FAVORITE_VIDEOS'));
			}
			$user = \Session::get("User");
			$videomember = MemberVideoModel::where('member_Id', '=', $user->user_id)->first();
			if ($videomember != NUll) {
				$videoid = explode(',', $videomember->video_Id);
				$video = VideoModel::whereIn('string_Id', $videoid)->paginate(perPage());
				if ($video) {
					return view('login.membervideo')->with('video', $video);
				}
			}
			return view('login.membervideo')->with('msgmember', trans('home.ADD_TO_MY_FAVORITE_VIDEOS'));
		}

		return 0;
	}

	/**
	 * [Get member subscriber]
	 * @return [resource] [description]
	 */
	public function get_membersubscribe() {
		$categories = CategoriesModel::where('status', '=', '1')
						->orderby('title_name', 'asc')
						->get();
		if (\Session::has("User")) {
			$user = \Session::get("User");
			$channelid = array();
			$memberid = ChanneSubscriberModel::where('status', '=', '1')->get();
			foreach ($memberid as $key) {
				$getid = explode(',', $key->member_Id);
				for ($i = 0; $i < count($getid); $i++) {
					if ($getid[$i] == $user->id) {
						array_push($channelid, $key->channel_Id);
					}
				}
			}

			$getchannel = ChannelModel::whereIn('ID', $channelid)->paginate(perPage());
			return view('login.membersubscribe')->with('getchannel', $getchannel)->with('categories', $categories);
		}
		return response()->json([
								'message' => 'Your account was expired'
								], 401);
	}

	/**
	 * [Get member payment history]
	 * @return [resource] [description]
	 */
	public function get_member_payment_hitory() {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$data = SubsriptionModel::select('subscription.*',
			DB::raw('(table_subscription.initialPeriod - DATEDIFF(now(),table_subscription.timestamp))  as expired'),
			DB::raw('IF(ISNULL(table_channel.title_name),table_video.title_name,table_channel.title_name) as title_name'), 'video.post_name as video_slug',
			DB::raw('CONCAT(table_users.firstname, " ", table_users.lastname) as customer_name'))
						->leftJoin('channel', 'channel.id', '=', 'subscription.channel_id')
						->leftJoin('users', 'users.id', '=', 'subscription.user_id')
						->leftJoin('video', 'video.string_Id', '=', 'subscription.video_id')
						->where('subscription.user_id','=',$user->user_id)
						->orderBy('timestamp', 'desc')
						->get();
			return view('login.membersetting')->with('transaction', 'payment history template')->with('data',$data);
		}
		return response()->json([
								'message' => 'Your account was expired'
								], 401);
	}

	/**
	 * [Get member]
	 * @return [type] [description]
	 */
	public function get_member() {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$getmember = MemberModel::find($user->id);
			return view('login.membersetting')->with('getmember', $getmember)->with('countmessage', $this->get_newmessage())->with('countfriend', $this->get_new_friend());
		}
		return response()->json([
								'message' => 'Your account was expired'
								], 401);
	}

	/**
	 * [get_changepassword description]
	 * @return [type] [description]
	 */
	public function get_changepassword() {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			return view('login.membersetting')->with('gettemp', 'Password Change template');
		}
		return response()->json([
								'message' => 'Your account was expired'
								], 401);
	}

	/**
	 * [post_changepassword description]
	 * @param  Request $req [description]
	 * @return [type]       [description]
	 */
	public function post_changepassword(Request $req) {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			if (\Request::Ajax()) {
				$rules = [
					'currentpass'          => 'required|min:6:max:32',
					'newpass'              => 'required|min:6:max:32|confirmed',
					'newpass_confirmation' => 'required|min:6:max:32'
				];
				$validator = Validator::make($req->all(), $rules);
				if ($validator->fails()) {
					return response()->json([
											'status_code' => 422,
											'message' => $validator->errors()->all()[0]
													], 422);
				}

				$checkPass = MemberModel::find($user->id);

				if ($checkPass->password !== md5($req->currentpass)) {
					return response()->json([
											'status_code' => 404,
											'message' => trans('home.CURRENT_PASSWORD_NOT_MATCH')
													], 404);
				}
				$checkPass->password = md5($req->newpass);

				if ($checkPass->save()) {
					MemberModel::setUserOffOnline($user->email);
					//set activity logs
					$log = new ActivityLogModel();
					$log->user_id = $user->id;
					$log->type = 'logout';
					$log->object_id = $user->id;
					$log->description = 'Signout at ' . date('Y-m-d');
					$log->save();
					return response()->json([
											'status_code' => 200,
											'message' => trans('home.PLEASE_LOGIN_OR_SIGNUP_FOR_USE')
													], 200);
				}
			}
		}
		return redirect(getLang() . 'login.html');
	}

	/**
	 * [get_newmessage description]
	 * @return [type] [description]
	 */
	public function get_newmessage() {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$newmessage = MemberMessageModel::where('tomember', '=', $user->user_id)->where('message_status', '=', 1)->count();
			return $newmessage;
		}
	}

	/**
	 * [get_message description]
	 * @return [type] [description]
	 */
	public function get_message() {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$allmessage = MemberMessageModel::select('members.firstname', 'members.lastname', 'members.user_id', 'member_message.*')
							->where('member_message.tomember', '=', $user->user_id)
							->join('members', 'members.user_id', '=', 'member_message.frommember')
							->groupBy('member_message.frommember')
							->paginate(perPage());
			//var_dump(count($allmessage));die;
			return view('login.membermessage')->with('allmessage', $allmessage);
		}
		return 0;
	}

	/**
	 * [post_deletemessage description]
	 * @param  [type] $postid [description]
	 * @return [type]         [description]
	 */
	public function post_deletemessage($postid) {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$delete = MemberMessageModel::where('tomember', '=', $user->user_id)->where('ID', '=', $postid)->delete();
			if ($delete) {
				return trans('home.DELETED_SUCESSFULLY');
			}
		}
	}

	/**
	 * [get_new_friend description]
	 * @return [type] [description]
	 */
	public function get_new_friend() {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$new_friend = MemberFriendModel::where('status', '=', 0)->where('member_Id', '=', $user->user_id)->count();
			return $new_friend;
		}
	}

	/**
	 * [get_friend description]
	 * @return [type] [description]
	 */
	public function get_friend() {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$allfriend = MemberFriendModel::select('ID', 'status', 'member_friend')
							->where('member_Id', '=', $user->user_id)
							->paginate(perPage());
			return view('login.memberfriend')->with('allfriend', $allfriend);
		} else {
			return 0;
		}
	}

	/**
	 * [get_friend_status description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function get_friend_status($id) {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$checkstatus = MemberFriendModel::where('member_Id', '=', $user->user_id)
											->where('member_friend', '=', $id)->first();
			if ($checkstatus) {
				if ($checkstatus->status == 1) {
					$friend_status = MemberFriendModel::where('member_Id', '=', $user->user_id)
													->where('member_friend', '=', $id)->update(array('status' => 2));
					return $friend_status;
				}if ($checkstatus->status == 2) {
					$friend_status = MemberFriendModel::where('member_Id', '=', $user->user_id)
													->where('member_friend', '=', $id)->update(array('status' => 1));

					return $friend_status;
				}
				if ($checkstatus->status == 0) {
					$friend_status = MemberFriendModel::where('member_Id', '=', $user->user_id)
													->where('member_friend', '=', $id)->update(array('status' => 1));
					$member_friend_status = MemberFriendModel::where('member_Id', '=', $id)
													->where('member_friend', '=', $user->user_id)->update(array('status' => 1));
					return $friend_status;
				}
			}
		}
	}

	/**
	 * [post_reply_message description]
	 * @param  Request $get [description]
	 * @return [type]       [description]
	 */
	public function post_reply_message(Request $get) {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$add_reply = new MemberMessageModel();
			$add_reply->frommember = $user->user_id;
			$add_reply->tomember = $get->friendmember;
			$add_reply->message = $get->reply_text;
			$add_reply->message_status = 4;
			if ($add_reply->save()) {
				$update_status_message_to_read_2 = MemberMessageModel::where('frommember', '=', $user->user_id)->where('message_status', '=', '1')->update(array('message_status' => 2));
				$update_status_message_to_read_1 = MemberMessageModel::where('tomember', '=', $user->user_id)->where('message_status', '=', '1')->update(array('message_status' => 2));

				if ($update_status_message_to_read_2 and $update_status_message_to_read_1) {
					return 1;
				}
			}
			return 0;
		}
		return 0;
	}

	/**
	 * [get_msg_list description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function get_msg_list($id) {
		return view('login.loadingmsg')->with('frommember', $id);
	}

	/**
	 * [signup description]
	 * @param  Request $req [description]
	 * @return [type]       [description]
	 */
	public function signup(Request $req) {

		if (\Request::ajax() != NULL) {
			$get_temp = EmailSettingModel::get_temp_confirm_sign_up();

			$firstname = $req->firstname;
			$sex = isset($req->sex) ? $req->sex : 0;
			$lastname = $req->lastname;
			$username = $req->username;
			$passwords = $req->passwords;
			$passwordagain = $req->passwordagain;
			$emails = $req->emails;
			$_token = md5($req->username);
			;
			if (strlen($firstname) < 4 or strlen($firstname) > 32) {
				return 1;
			}
			if ($firstname == NULL or $firstname == '') {
				return 2;
			}
			if (strlen($lastname) < 4 or strlen($lastname) > 32) {
				return 3;
			}
			if ($lastname == NULL or $lastname == '') {
				return 4;
			}
			if ($username == NULL or $username == '') {
				return 11;
			}
			if (strlen($username) < 4 or strlen($username) > 32) {
				return 12;
			}
			$checkuser = UserSignupModel::where('username', '=', $username)->get();
			$checkmemberuser = MemberModel::where('username', '=', $username)->get();
			if (count($checkuser) > 0 or count($checkmemberuser) > 0) {
				return 5;
			}
			if ($passwords == NULL or $passwords == '') {
				return 7;
			}
			if (strlen($passwords) < 6) {
				return 6;
			}
			if ($passwordagain == NULL or $passwordagain == '') {
				return 8;
			}
			if ($passwordagain != $passwords) {
				return 9;
			}
			if ($emails == NULL or $emails == '') {
				return 13;
			}
			$checkemail = UserSignupModel::where('email', '=', $emails)->get();
			$checkmemberemail = MemberModel::where('email', '=', $emails)->get();
			if (count($checkemail) > 0 or count($checkmemberemail) > 0) {
				return 10;
			}if (!filter_var($emails, FILTER_VALIDATE_EMAIL)) {
				return 14;
			} else {
				$newuser = new UserSignupModel ();
				$newuser->username = $username;
				$newuser->password = md5($passwords);
				$newuser->firstname = $firstname;
				$newuser->lastname = $lastname;
				$newuser->email = $emails;
				$newuser->remember_token = $_token;

				if ($newuser->save()) {
					\Session::put("UserNew", $newuser);
					$user = \Session::get('UserNew');
					$newmember = new MemberModel();
					$newmember->user_id = $user->id;
					$newmember->username = $username;
					$newmember->password = md5($passwords);
					$newmember->email = $emails;
					$newmember->firstname = $firstname;
					$newmember->lastname = $lastname;
					$newmember->status = 0;
					$newmember->sex = $sex;
					if ($newmember->save()) {

						$config = OptionModel::get_config();
						$sendmail = Mail::send(
							'admincp.mail.' . $get_temp->name_slug . '',
							array('firstname' => $firstname, 'lastname' => $lastname, 'token' => $_token, 'site_name' => $config->site_name),
							function($message) use($req) {
								$message->to($req->emails)->subject('Welcome To Adult Video ');
							}
						);
						if ($sendmail) {
							return 0;
						}
					}
				}
			}
		}
	}

	/**
	 * [get_confrim_active description]
	 * @param  [type] $token [description]
	 * @return [type]        [description]
	 */
	public function get_confrim_active($token) {
		if ($token != NULL) {
			$check_token = UserSignupModel::where('remember_token', '=', $token)->first();
			if ($check_token != NULL) {
				$update = MemberModel::where('user_id', '=', $check_token->id)->update(array('status' => 1));
				if ($update) {
					$get_member = MemberModel::where('user_id', '=', $check_token->id)->first();
					\Session::put('User', $get_member);
					return redirect('')->with('msg', trans('home.ACCOUNT_IS_ACTIVE'));
				}
				return redirect('')->with('msg', trans('home.ACCOUNT_IS_ACTIVE_FAILED'));
			}
		}
	}

	/**
	 * [get_viewedit description]
	 * @return [type] [description]
	 */
	public function get_viewedit() {

		if (\Session::has('User')) {
			$user = \Session::get('User');
			$get_user = MemberModel::where('user_id', '=', $user->user_id)->first();
			return view('login.editmember')->with('user', $get_user);
		}
		return 0;
	}

	/**
	 * [uploadAvatar description]
	 * @param  Request $get [description]
	 * @return [type]       [description]
	 */
	public function uploadAvatar(Request $get)
	{
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$file = Input::file()[0];
			$fileType = $file->getClientOriginalExtension();

			if($fileType != 'jpeg' && $fileType != 'jpg' && $fileType != 'png') {
				return response()->json(['error' => 'Cannot upload '. $fileType . ' file'], 422);
			}

			if ($file->isValid()) {
				$member = MemberModel::find($user->id);
				if (!empty($member->avatar)) {
					if (File::exists(public_path() . '/upload/member' . $member->avatar)) {
						unlink(public_path() . '/upload/member' . $member->avatar);
					}
				}
				$path = public_path() . '/upload/member';

				$extensionTemplate = $file->getClientOriginalExtension();

				$fileNameTemplate = $file->getClientOriginalName();

				$file->move($path, $fileNameTemplate);
				$member->avatar = $fileNameTemplate;
				$member->save();
			}
			return;
		}
		return response()->json([
								'status_code' => 401,
								'messge' => trans('home.PLEASE_LOGIN_OR_SIGNUP_FOR_USE')
										], 401);
	}

	/**
	 * [post_viewedit description]
	 * @param  Request $req [description]
	 * @return [type]       [description]
	 */
	public function post_viewedit(Request $req) {
		if (\Session::has('User')) {
			$user = \Session::get('User');

			if (\Request::ajax()) {

				$rules = [
						'firstname' => 'required|min:4',
						'lastname' => 'required|min:4',
						'emails' => 'required|email',
				];

				$validator = Validator::make($req->all(), $rules);
				if ($validator->fails()) {
					return response()->json([
											'status_code' => 422,
											'message' => $validator->errors()->all()[0]
													], 422);
				}

				$firstname = $req->firstname;
				$lastname = $req->lastname;
				$emails = $req->emails;
				$birthdate = $req->birthdate;
				$bio = $req->bio;
				$is_comment = isset($req->is_comment) ? 1 : 0;
				$address = $req->address;
				$member = MemberModel::where('user_id', '=', $user->user_id)->update(array(
						'firstname' => $firstname,
						'lastname' => $lastname,
						'email' => $emails,
						'birthdate' => $birthdate,
						'address' => $address,
						'bio' => $bio,
						'is_comment' => $is_comment
				));

				if ($member) {
					return response()->json([
											'status_code' => 200,
											'message' => trans('home.UPDATED_SUCCESSFULLY')
													], 200);
				}
			}
		}
		return response()->json([
								'status_code' => 401,
								'message' => trans('home.PLEASE_LOGIN_OR_SIGNUP_FOR_USE')
										], 401);
	}

	/**
	 * [get_view_member_profile description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function get_view_member_profile($id) {
		if (\Session::has('User')) {
			$categoris = CategoriesModel::where('status', '=', '1')
							->orderby('title_name', 'asc')
							->get();
			$getmember_profile = MemberModel::where('user_id', '=', $id)->first();
			return view('member.memberprofile')
											->with('categories', $categoris)
											->with('member', $getmember_profile)
											->with('checkfriend', $this->check_member_friend($id))
											->with('get_comment', $this->get_comment($id));
		} else {
			return view('errors.auth');
		}
	}

	/**
	 * [post_add_friend description]
	 * @param  Request $get [description]
	 * @return [type]       [description]
	 */
	public function post_add_friend(Request $get) {

		if (\Session::has('User')) {
			$user = \Session::get('User');
			$friendid = $get->id;
			$check = MemberFriendModel::where('member_Id', '=', $user->user_id)
											->where('member_friend', '=', $friendid)->first();
			if (empty($check)) {
				$addfriend = new MemberFriendModel();
				$addfriend->member_Id = $user->user_id;
				$addfriend->member_friend = $get->id;
				$addfriend->status = 0;
				$addfriend_sync = new MemberFriendModel();
				$addfriend_sync->member_Id = $get->id;
				$addfriend_sync->member_friend = $user->user_id;
				$addfriend_sync->status = 0;
				if ($addfriend->save() && $addfriend_sync->save()) {
					return trans('home.ADD_FRIEND_SUCCESSFULLY');
				}
			}
		} else {
			return trans('home.PLEASE_LOGIN_OR_SIGNUP_FOR_USE');
		}
	}

	/**
	 * [post_un_friend description]
	 * @param  Request $get [description]
	 * @return [type]       [description]
	 */
	public function post_un_friend(Request $get) {

		if (\Session::has('User')) {
			$user = \Session::get('User');
			$friendid = $get->id;
			$check_friend = MemberFriendModel::where('member_Id', '=', $user->user_id)
											->where('member_friend', '=', $friendid)->delete();
			$check_member = MemberFriendModel::where('member_friend', '=', $user->user_id)
											->where('member_friend', '=', $friendid)->delete();
			return trans('home.UN_FRIEND_SUCCESSFULLY');
		} else {
			return trans('home.PLEASE_LOGIN_OR_SIGNUP_FOR_USE');
		}
	}

	/**
	 * [check_member_friend description]
	 * @param  [type] $friendid [description]
	 * @return [type]           [description]
	 */
	public function check_member_friend($friendid) {
		$user = \Session::get('User');
		$check = MemberFriendModel::where('member_Id', '=', $user->user_id)
										->where('member_friend', '=', $friendid)->first();
		return $check;
	}

	/**
	 * [get_send_message description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function get_send_message($id) {
		return view('member.sendmessage')->with('memberid', $id);
	}

	/**
	 * [post_send_message description]
	 * @param  Request $get [description]
	 * @param  [type]  $id  [description]
	 * @return [type]       [description]
	 */
	public function post_send_message(Request $get, $id) {
		if (\Request::ajax()) {
			$user = \Session::get('User');
			if ($get->message == NULL OR $get->message == '') {
				return 0;
			} else {
				$message = new MemberMessageModel();
				$message->frommember = $user->user_id;
				$message->tomember = $id;
				$message->message = $get->message;
				$message->message_status = 1;
				if ($message->save()) {
					return 1;
				}
			}
		}
	}

	/**
	 * [get_video_profile description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function get_video_profile($id) {
		$videomember = MemberVideoModel::where('member_Id', '=', $id)->first();
		if ($videomember != NUll) {
			$videoid = explode(',', $videomember->video_Id);
			$video = VideoModel::whereIn('string_Id', $videoid)->paginate(6);
			if ($video) {
				return view('member.membervideo')->with('video', $video)->with('memberid', $id);
			}
		}
		return view('member.membervideo')->with('msgmember', trans('home.VIDEO_NOT_FOUND'));
	}

	/**
	 * [get_subscribe_profile description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function get_subscribe_profile($id) {

		$channelid = array();
		$memberid = ChanneSubscriberModel::where('status', '=', '1')->get();
		foreach ($memberid as $key) {
			$getid = explode(',', $key->member_Id);
			for ($i = 0; $i < count($getid); $i++) {
				if ($getid[$i] == $id) {
					array_push($channelid, $key->channel_Id);
				}
			}
		}
		$getchannel = ChannelModel::whereIn('ID', $channelid)->paginate(2);
		return view('member.membersubscribe')->with('getchannel', $getchannel)->with('memberid', $id);
	}

	/**
	 * [post_mail_forgot description]
	 * @param  Request $get [description]
	 * @return [type]       [description]
	 */
	public function post_mail_forgot(Request $get) {
		$rule = [
				'email' => 'required|email|exists:members,email'
		];
		$validator = Validator::make($get->all(), $rule);
		if ($validator->fails()) {
			return response()->json([
									'status_code' => 422,
									'message' => $validator->errors()->all()[0]
											], 422);
		}

		$get_email_temp = EmailSettingModel::get_temp_member_reset_password();
		$get_config = AppHelper::getSiteConfig();
		$get_user = MemberModel::where('email', '=', $get->email)->first();
		$newpass = str_random(8);
		if ($get_user != NULL && $get_user->email == $get->email) {
			$sendmail = Mail::send('admincp.mail.' . $get_email_temp->name_slug . '', array(
									'newpassword' => $newpass,
									'lastname' => $get_user->lastname,
									'firstname' => $get_user->firstname,
									'site_email' => $get_config->site_email,
									'site_phone' => $get_config->site_phone,
									'site_name' => $get_config->site_name,
									'userid' => $get_user->user_id), function($message) use($get) {
								$message->to($get->email)->subject('Get New Password');
							});
			if ($sendmail) {
				$updatepassword = MemberModel::where('id', '=', $get_user->id)->update(array('password' => md5($newpass)));
				if ($updatepassword) {
					return response()->json([
											'status_code' => 200,
											'message' => trans('home.SEND_MAIL_SUCCESSFULLY')
													], 200);
				}
			}
			return response()->json([
									'status_code' => 500,
									'message' => 'System error. Please try again.'
											], 500);
		}
	}

	/**
	 * [get_friend_member description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function get_friend_member($id) {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			if ($id != NULL) {
				$get_member = MemberModel::where('user_id', '=', $id)->first();
				$get_friend = MemberFriendModel::where('member_Id', '=', $id)->get();
				return view('member.memberfriend')->with('friend', $get_friend)->with('member', $get_member);
			}
			return 0;
		}
		return 1;
	}

	/**
	 * [post_report_user description]
	 * @param  Request $get [description]
	 * @return [type]       [description]
	 */
	public function post_report_user(Request $get) {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$check_reported = MemberReportModel::where('user_id', '=', $user->user_id)->where('status', '=', 2)->count();
			if ($check_reported > 0) {
				return 4;
			}

			if ($get) {
				$report_content = $get->report_content;
				$memberid = $get->memberid;
				if ($report_content != NULL) {
					$addnew = new MemberReportModel();
					$addnew->user_id = $user->user_id;
					$addnew->member_Id = $memberid;
					$addnew->content = $report_content;
					$addnew->status = 2;
					$addnew->member_status = 0;
					if ($addnew->save()) {
						$count_report = MemberReportModel::where('status', '=', 2)
										->where('member_Id', '=', $memberid)
										->where('user_id', '<>', $user->user_id)
										->count();
						if ($count_report == 4) {
							$update_status = MemberReportModel::where('status', '=', 2)
															->where('member_Id', '=', $memberid)->update(array('status' => 1, 'member_status' => 1));
						}
						return 1;
					}
				}
				return 2;
			}
			return 0;
		}
		return 3;
	}

	public function post_block_user(Request $get) {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$check_blocked = MemberReportModel::where('user_id', '=', $user->user_id)->where('status', '=', 1)->count();
			if ($check_blocked > 0) {
				return 4;
			}
			if ($get) {
				$block_content = $get->block_content;
				$memberid = $get->memberid;
				if ($block_content != NULL) {
					$addnew = new MemberReportModel();
					$addnew->user_id = $user->user_id;
					$addnew->member_Id = $memberid;
					$addnew->content = $block_content;
					$addnew->status = 1;
					$addnew->member_status = 0;
					if ($addnew->save()) {
						$count_report = MemberReportModel::where('status', '=', 1)
										->where('member_Id', '=', $memberid)
										->where('user_id', '<>', $user->user_id)
										->count();
						if ($count_report == 4) {
							$update_status = MemberReportModel::where('status', '=', 1)
															->where('member_Id', '=', $memberid)->update(array('status' => 1, 'member_status' => 1));
						}
						return 1;
					}
				}
				return 2;
			}
			return 0;
		}
		return 3;
	}

	public function post_private_msg(Request $get) {
		if ($get) {
			if (\Session::has('User')) {

				if ($get->msg_email == NULL or $get->msg_email == "") {
					return 0;
				}
				if (!filter_var($get->msg_email, FILTER_VALIDATE_EMAIL)) {
					return 1;
				}
				if ($get->msg_content == NULL or $get->msg_content == "") {
					return 3;
				}
				if (!preg_match("/^[a-zA-Z 0-9ss,.!~?''\s]*$/", $get->msg_content)) {
					return 2;
				}

				$user = \Session::get('User');
				$checkexit = MSGPrivateModel::where('user_id', '=', $user->user_id)->where('status', '=', 0)->first();
				if ($checkexit == NULL) {
					$add = new MSGPrivateModel();
					$add->user_id = $user->user_id;
					$add->string_id = $get->string_id;
					$add->email = $get->msg_email;
					$add->content = $get->msg_content;
					$add->status = 0;
					if ($add->save()) {
						$getoption = AppHelper::getSiteConfig();
						$sendmail = Mail::send('admincp.mail.mailmsg', array('name' => 'Administrator'), function($message) use($getoption) {
											$message->to($getoption->site_email)->subject('Message Form website');
										});
						if ($sendmail) {
							return 4;
						}
					}
				}
				return 5;
			}
		}
	}

	public function post_comment(Request $req) {

		if (\Session::has('User')) {
			if (\Request::Ajax()) {
				$user = \Session::get('User');
				$comment = $req->profile_comment_text;
				$profileid = $req->profileid;
				$addcomment = new ProfileCommentModel();
				$addcomment->profile_Id = $profileid;
				$addcomment->member_post_id = $user->user_id;
				$addcomment->post_comment = $comment;
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
		}
		return trans('home.PLEASE_LOGIN_TO_COMMENT');
	}

	public function get_comment($profileid) {
		if (\Session::has('User')) {
			$user = \Session::get('user');
			$getcomment = ProfileCommentModel::select('profile_comment.*', 'members.firstname', 'members.lastname', 'members.avatar', 'members.user_id')
							->where('profile_Id', '=', $profileid)
							->join('members', 'profile_comment.member_post_id', '=', 'members.user_id')
							->orderby('profile_comment.ID', 'DESC')
							->paginate(4);

			return $getcomment;
		}
		return trans('home.PLEASE_LOGIN_TO_COMMENT');
	}

	public function get_503() {
		$get_config = AppHelper::getSiteConfig();
		return view('errors.503')->with('config', $get_config);
	}

	public function post_payment_thank_you_page() {

		if ($_POST) {

			$myfile = fopen(public_path("upload/payment.txt"), "w") or die("Unable to open file!");
			$txt = json_encode($_POST);
			fwrite($myfile, $txt);
			fclose($myfile);

			$add = new SubsriptionModel();
			$add->user_id = $_POST['user_id'];
			if (isset($_POST['video'])) {
				$add->video_id = $_POST['video'];
			}
			if (isset($_POST['channel'])) {
				$add->channel_id = $_POST['channel'];
			}
			$add->subscriptionId = $_POST['subscription_id'];
			if (isset($_POST['transactionId'])) {
				$add->transactionId = $_POST['transactionId'];
			}
			$add->email = $_POST['email'];
			$add->priceDescription = $_POST['price'];
			$add->subscriptionTypeId = $_POST['typeId'];
			$add->timestamp = $_POST['start_date'];
			$add->ipAddress = $_POST['ip_address'];
			if (isset($_POST['paymentType'])) {
				$add->paymentType = $_POST['paymentType'];
			}
			$add->subscriptionInitialPrice = $_POST['initialPrice'];
			$add->subscriptionCurrency = $_POST['baseCurrency'];
			$add->initialPeriod = $_POST['initialPeriod'];
			$result = $add->save();

			if ($result) {
				if (isset($_POST['channel'])) {
					$channel_id = $_POST['channel'];
					$user_id = $_POST['user_id'];

					$user = MemberModel::whereUserId($user_id)->first();

					$channel_subscriber = ChanneSubscriberModel::whereChannelId($channel_id)->first();
					if ($channel_subscriber) {
						$channel_subscriber->member_Id .= ',' . $user->id;
					} else {
						$channel_subscriber = new ChanneSubscriberModel();
						$channel_subscriber->channel_Id = $channel_id;
						$channel_subscriber->member_Id = $user->id;
						$channel_subscriber->status = 1;
					}

					if ($channel_subscriber->save()) {
						$channel        = ChannelModel::find($channel_id);
						$channelOwner   = MemberModel::whereUserId($channel->user_id)->first();
						$getoption      = AppHelper::getSiteConfig();
						$get_email_temp = EmailSettingModel::get_channel_subscribe_user_email();
						$emails = [ $user->email, $channelOwner->email, UsersModel::getAdminEmail()];
						$sendmail = Mail::send('admincp.mail.'.$get_email_temp->name_slug.'',array(
							'firstname'=>$user->firstname,
							'lastname'=>$user->lastname,
							'site_name'=>$getoption->site_name,
							'site_phone'=>$getoption->site_phone,
							'site_email'=>$getoption->site_email,
							'channel_name'=>$channel->title_name
							),function($message) use($emails){
							$message->to($emails)->subject('Congratulation! Subscribed Adult Streaming Website Channel!');
						});
					}
				} else {
					$video = $_POST['video'];
					$user_id = $_POST['user_id'];

					$member_video = MemberVideoModel::whereMemberId($user_id)->first();
					if ($member_video) {
						$member_video->video_Id .= ',' . $video;
					} else {
						$member_video = new MemberVideoModel();
						$member_video->member_Id = $user_id;
						$member_video->video_Id = $video;
						$member_video->status = 1;
					}

					$member_video->save();
				}
			}
		}
	}

	public function complete_payment() {
		$user           = \Session::get('User');
		$getoption      = AppHelper::getSiteConfig();
		$get_email_temp = EmailSettingModel::get_payment_email();
		$emails         = [ $user->email, UsersModel::getAdminEmail()];
		$sendmail = Mail::send('admincp.mail.'.$get_email_temp->name_slug.'',array(
			'firstname'=>$user->firstname,
			'lastname'=>$user->lastname,
			'site_name'=>$getoption->site_name,
			'site_phone'=>$getoption->site_phone,
			'site_email'=>$getoption->site_email
			),function($message) use($emails){
			$message->to($emails)->subject('Congratulation! Paid Video From Adult Streaming Website Successful!');
		});
		return redirect(getLang() . 'member-proflie.html')->with('msg', 'Thank you for payment !');
	}

	public function get_channel_subscription() {
		if (\Session::has('User')) {
			$user = \Session::get('User');

			$html = "";
			$get_payment = SubsriptionModel::select('subscription.*', 'channel.title_name', 'video.title_name as video_name', 'video.post_name as video_slug')
							->where('subscription.user_id', '=', $user->user_id)
							->leftJoin('channel', 'channel.ID', '=', 'subscription.channel_id')
							->leftJoin('video', 'video.string_Id', '=', 'subscription.video_id')
							->paginate(5);
			if (count($get_payment) > 0) {
				$i = 1;
				$html.='<h2>All Channel Subsription</h2>';
				$html.='<table class="table table-striped">
							<thead>
									<tr>
											<th>#</th>
											<th>subsription</th>
											<th>Price</th>
											<th>Description</th>
											<th>Active date</th>
											<th>Status</th>
									</tr>
							</thead>
							<tbody>';

				foreach ($get_payment as $result) {
					$today = strtotime(date('Y-m-d H:i:s'));
					$expireDay = strtotime($result->timestamp);
					$timeToEnd = $today - $expireDay;
					$countdate = date('d', $timeToEnd);
					$html.='<tr>';
					$html.='
														<td>' . $i++ . '</td>';
					if ($result->title_name != NULL) {
						$html.='<td><a style="color:#fff" href="' . URL('channel/' . $result->channel_id . '/' . str_slug($result->title_name) . '') . '">' . $result->title_name . '</a></td>';
					} else {
						$html.='<td><a style="color:#fff" href="' . URL('watch/' . $result->video_id . '/' . $result->video_slug . '') . '.html">' . $result->video_name . '</a></td>';
					}
					$html.='<td>' . $result->subscriptionInitialPrice . ' ' . $result->subscriptionCurrency . '</td>
																				<td title="' . $result->priceDescription . '">' . str_limit($result->priceDescription, 30) . '</td>
																				<td>' . $result->timestamp . '</td>';
					if ($result->initialPeriod >= $countdate) {
						$html.='<td><span class="label label-success">Active</span></td>';
					} else {
						$html.='<td><span class="label label-danger">Inactive</span></td>';
					}
					$html.='</tr>';
				}
				$html.='</tr></tbody></table>';
				return $html;
			}
			return 1;
		}
		return 0;
	}

	public function get_channel() {
		// die('dsds');
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$user_id = $user->user_id;
			$channel = ChannelModel::where('user_id', '=', $user_id)->first();
			if ($channel != NULL) {
				return view('login.channel')->with('gettemp', 'Password Change template')->with('channel', $channel);
			}
			return 1;
		}
		return 0;
	}

	public function edit_channel(Request $data) {
		$user_id = $data->user;
		$file = $data->file;
		$channel = ChannelModel::where('user_id', '=', $user_id)->first();
		$rule = array(
				'titlename' => 'required|max:255|min:4',
				'description' => 'required',
		);
		$validator = Validator::make($data->all(), $rule);
		if ($validator->fails()) {
			$messages = $validator->errors();
			return view('login.form_error')->withErrors($validator);
		}
		if (count($channel) > 0) {
			$data = array(
					'title_name' => $data->titlename,
					'post_name' => str_slug($data->titlename),
					'description' => $data->description,
					'subscribe_status' => isset($data->subcribe) ? 1 : 0,
					'status' => $data->status,
					'tag' => $data->tag,
			);
			if ($file) {
				$updated_poster = ChannelModel::where('user_id', '=', $user_id)->update(array('poster' => $file));
			}
			$updated = ChannelModel::where('user_id', '=', $user_id)->update($data);
			if ($updated) {
				return '<div class="alert alert-success">' . trans('home.UPDATED_SUCCESSFULLY') . '</div>';
			}
		}
		return '<div class="alert alert-danger">System not work. Please try again!!</div>';
	}

	public function get_sitemap() {
		$channel = ChannelModel::where('status', '=', 1)->get();
		$categoris = CategoriesModel::where('status', '=', '1')
						->orderby('title_name', 'asc')
						->get();

		return view('sitemap.sitemap')->with('categories', $categoris)->with('channel', $channel);
	}

	public function get_contact() {
		$channel = ChannelModel::where('status', '=', 1)->get();
		$categoris = CategoriesModel::where('status', '=', '1')
						->orderby('title_name', 'asc')
						->get();

		return view('contact.index')->with('categories', $categoris);
	}

	public function post_contact(Request $get) {
		$categoris = CategoriesModel::where('status', '=', '1')
						->orderby('title_name', 'asc')
						->get();
		$rules = array(
				'email_contact'   => 'required|email',
				'name_contact'    => 'required',
				'account_contact' => 'required',
				'type_contact'    => 'required',
				'message_contact' => 'required'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			$messages = $validator->messages();
			return view('contact.index')
											->with('validator', $messages)->with('categories', $categoris);
		}

		if ($this->captchaCheck() == false) {
			return redirect(getLang() . 'contact')->with('captcha', 'Please verify Captchar!');
		}
		$add = new ContactModel();
		$add->email = $get->email_contact;
		$add->name = $get->name_contact;
		$add->type = $get->type_contact;
		$add->message = $get->message_contact;
		$add->status = 1;
		if ($add->save()) {
			return redirect(getLang() . 'contact')->with('message', 'Your contact has been sent !');
		}
	}

	public function captchaCheck() {

		$response = Input::get('g-recaptcha-response');
		$remoteip = $_SERVER['REMOTE_ADDR'];
		$secret = env('RE_CAP_SECRET');

		$recaptcha = new \ReCaptcha\ReCaptcha($secret);
		$resp = $recaptcha->verify($response, $remoteip);
		if ($resp->isSuccess()) {
			return true;
		}
		return false;
	}

	public function get_FQA() {
		$fqa_list = FQAModel::get_list();
		return view('footer.fqa')->with('fqa', $fqa_list);
	}

	public function get_channel_dashboard() {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$channel_user = ChannelModel::where('user_id', '=', $user->user_id)->first();
			if ($channel_user != NULL) {
				return view('login.channel_dashboard')
							->with('total_video', $this->get_channel_total_video($channel_user->ID))
							->with('total_subscriber', $this->get_total_subscriber_channel($channel_user->ID))
							->with('channel_user', $channel_user);
			}
			return "No_Channel";
		}
		return trans('home.PLEASE_LOGIN_OR_SIGNUP_FOR_USE');
	}

	public function get_channel_total_video($id) {
		$total = VideoModel::where('channel_Id', '=', $id)->count();
		return $total;
	}

	public function get_total_subscriber_channel($id) {
		$subscriber = ChanneSubscriberModel::where('channel_Id', '=', $id)->first();
		//var_dump($subscriber);die;
		if ($subscriber != NULL) {
			$total = explode(',', $subscriber->member_Id);
			return $total;
		}
		return NULL;
	}

	public function remove_videoFav($id)
	{
		if (\Session::has("User")) {
			$video = VideoModel::where('string_Id', $id)->first();
			if(empty($video)) {
				return response()->json(['status' => 404, 'message' => 'Video not found']);
			}
			$user = \Session::get("User");
			$videosMember = MemberVideoModel::where('member_Id', $user->user_id)->first();
			if($videosMember) {
				$listVideosFav = explode(",",$videosMember->video_Id);
				$index = array_search($id, $listVideosFav);
				if($index !== false) {
					array_splice($listVideosFav, $index, 1);
				}
				$videoFavString = implode(",",$listVideosFav);
				$videosMember->video_Id = $videoFavString;
				$videosMember->save();
				return response()->json(['status' => 200, 'message' => 'Remove Successful']);
			}
			return response()->json(['status' => 404, 'message' => 'Video Member not found']);
		}
		return response()->json(['status' => 402, 'message' => 'Not Author']);
	}

	public function remove_channelSub($id)
	{
		if (\Session::has("User")) {
			$channelSub = ChanneSubscriberModel::where('channel_Id', $id)->first();
			if(empty($channelSub)) {
				return response()->json(['status' => 404, 'message' => 'Channel subcribe not found']);
			}

			$user = \Session::get("User");
			$memberSubscribed = explode(",",$channelSub->member_Id);
			$index = array_search($user->id, $memberSubscribed);
			if($index !== false) {
				array_splice($memberSubscribed, $index, 1);
			}

			$memberIdString = implode(",",$memberSubscribed);
			$channelSub->update(['member_Id' => $memberIdString]);
			$status = $channelSub->save() ? 200 : 500;
			return response()->json(['status' => $status, 'message' => 'Remove Successful']);

		}

		return response()->json(['status' => 402, 'message' => 'Not Author']);
	}
}
