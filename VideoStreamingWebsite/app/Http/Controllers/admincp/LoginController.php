<?php

namespace App\Http\Controllers\admincp;

use Illuminate\Http\Request;
use App\Models\UsersModel;
use App\Models\MemberModel;
use App\Models\OptionModel;
use App\Models\StaticPageModel;
use App\Models\SubsriptionModel;
use App\Models\ChanneSubscriberModel;
use App\Models\PaymentConfigModel;
use App\Models\ConversionModel;
use App\Models\HeaderModel;
use App\Models\FQAModel;
use App\Models\ContactModel;
use App\Models\EmailSettingModel;
use App\Models\BanIPModel;
use App\Models\TagModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Helper\AppHelper;
use App\Helper\PageHelper;
use Cache;
use DB;

class LoginController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getLogin() {
		return View('admincp.login.login');
	}

	public function postLogin(Request $request) {
		$username = $request->input('username');
		$password = MD5($request->input('password'));
		$login = UsersModel::CheckLogin($username, $password);
		if ($login) {
			return redirect('admincp');
		}
		return View('admincp.login.login')->with('login_error', 'The Username or Password you entered is incorrect. !!!');
	}

	public function getLogout() {
		Session::forget('logined');
		return redirect('admincp/login');
	}

	public function get_option() {
		$get_option = AppHelper::getSiteConfig();
		return view('admincp.option.option')->with('option', $get_option);
	}

	public function post_option(Request $get) {
		if ($get) {
			$site_name = isset($get->site_name) ? $get->site_name : '';
			$site_description = isset($get->site_description) ? $get->site_description : '';
			$site_copyright = isset($get->site_copyright) ? $get->site_copyright : '';
			$site_keyword = isset($get->site_keyword) ? $get->site_keyword : '';
			$site_google_verification_code = isset($get->site_google_verification_code) ? $get->site_google_verification_code : '';
			$site_phone = isset($get->site_phone) ? $get->site_phone : '';
			$site_email = isset($get->site_email) ? $get->site_email : '';
			$site_fb = isset($get->site_fb) ? $get->site_fb : '';
			$site_tw = isset($get->site_tw) ? $get->site_tw : '';
			$site_text_footer = isset($get->site_text_footer) ? $get->site_text_footer : '';
			$site_linkin = isset($get->site_linkin) ? $get->site_linkin : '';
			$site_address = isset($get->site_address) ? $get->site_address : '';
			$site_ga = isset($get->site_ga) ? $get->site_ga : '';
			$perPage = isset($get->perPage) ? $get->perPage : '';


			if ($site_name != NULL) {
				if (!preg_match("/^[-a-z A-Z 0-9 @ = ~ _ | ! : , . ; ]*$/", $site_name)) {
					return redirect('admincp/option')->with('msgerror', ' Site title ONLY letters and white space allowed');
				}
			}
			if ($site_description != NULL) {
				if (!preg_match("/^[-a-z A-Z 0-9 @ = ~ _ | ! : , . ; ]*$/", $site_description)) {
					return redirect('admincp/option')->with('msgerror', ' description ONLY letters and white space allowed');
				}
			}
			if ($site_copyright != NULL) {
				if (!preg_match("/^[-a-z A-Z  0-9 ©&@=~_|!:,.; ]*$/", $site_copyright)) {
					return redirect('admincp/option')->with('msgerror', 'Copyright ONLY letters and white space allowed');
				}
			}
			if ($site_keyword != NULL) {
				if (!preg_match("/^[-a-zA-Z 0-9 @ = ~ _ | ! : , . ; ]*$/", $site_keyword)) {
					return redirect('admincp/option')->with('msgerror', 'Keyword ONLY letters and white space allowed');
				}
			}
			if ($site_phone != NULL) {
				if (!preg_match("/^[0-9 +]*$/", $site_phone)) {
					return redirect('admincp/option')->with('msgerror', 'Phone Input must be numberis');
				}
			}
			if ($site_email != NULL) {

				if (!filter_var($site_email, FILTER_VALIDATE_EMAIL)) {
					return redirect('admincp/option')->with('msgerror', 'email Invalid email format');
				}
			}
			if ($site_fb != NULL) {
				if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $site_fb)) {
					return redirect('admincp/option')->with('msgerror', 'Facebook Invalid website address format');
				}
			}
			if ($site_tw != NULL) {
				if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $site_tw)) {
					return redirect('admincp/option')->with('msgerror', ' Twitter Invalid website address format');
				}
			}
			if ($site_linkin != NULL) {
				if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $site_linkin)) {
					return redirect('admincp/option')->with('msgerror', ' Linkin Invalid website address format');
				}
			}
			$data = array(
				'site_name'                     => $site_name,
				'site_description'              => $site_description,
				'site_keyword'                  => $site_keyword,
				'site_google_verification_code' => $site_google_verification_code,
				'site_address'                  => $site_address,
				'site_phone'                    => $site_phone,
				'site_email'                    => $site_email,
				'site_fb'                       => $site_fb,
				'site_tw'                       => $site_tw,
				'site_linkin'                   => $site_linkin,
				'site_copyright'                => $site_copyright,
				'site_text_footer'              => $site_text_footer,
				'site_ga'                       => $site_ga,
				'perPage'                       => $perPage,
			);
			$site_map = Input::file('site_map');
			$site_logo = Input::file('site_logo');
			// $site_channel_logo = Input::file('site_channel_logo');
			if ($site_map) {
				$file = array('sitemap' => Input::file('site_map'));

				$rules = array(
					'sitemap' => 'mimes:xml',
				);

				$validator = Validator::make($file, $rules);
				if ($validator->fails()) {
					return redirect('admincp/option')->with('msgerro', $validator->errors()->all()[0]);
				}
				$extension = $site_map->getClientOriginalExtension();

				$Allowed = array("xml");

				$destinationPath = storage_path("app");

				$filename = "sitemap." . $extension;

				if (in_array($extension, $Allowed)) {
					$site_map->move($destinationPath, $filename);
					$data["site_map"] = $filename;
					// $update_sitemap = OptionModel::where('id', '=', $get->id)->update(array('site_map' => $filename));
				}
			}

			if ($site_logo) {
				$file = array('photo' => Input::file('site_logo'));

				$rules = array(
					'photo' => 'mimes:jpeg,png,gif',
				);

				$validator = Validator::make($file, $rules);
				if ($validator->fails()) {
					return redirect('admincp/option')->with('msgerro', $validator->errors()->all()[0]);
				}

				$extension = $site_logo->getClientOriginalExtension();

				$Allowed = array("png", "jpg", "gif", "PNG", "JPG", "GIF");

				$destinationPath = storage_path("app");

				$filename = "logo." . $extension;

				if (in_array($extension, $Allowed)) {
					$site_logo->move($destinationPath, $filename);
					$data["site_logo"] = $filename;
					// $update_logo = OptionModel::where('id', '=', $get->id)->update(array('site_logo' => $filename));
				}
			}

			// if ($site_channel_logo) {
			// 	$file = array('photo' => Input::file('site_channel_logo'));

			// 	$rules = array(
			// 		'photo' => 'mimes:jpeg,png,gif',
			// 	);

			// 	$validator = Validator::make($file, $rules);
			// 	if ($validator->fails()) {
			// 		return redirect('admincp/option')->with('msgerro', $validator->errors()->all()[0]);
			// 	}

			// 	$extension = $site_channel_logo->getClientOriginalExtension();

			// 	$Allowed = array("png", "jpg", "gif", "PNG", "JPG", "GIF");

			// 	$destinationPath = base_path() . "/";

			// 	$filename = "channel-logo." . $extension;

			// 	if (in_array($extension, $Allowed)) {
			// 		$site_channel_logo->move($destinationPath, $filename);
			// 		$data["site_channel_logo"] = $filename;
			// 		$update_logo = OptionModel::where('id', '=', $get->id)->update(array('site_channel_logo' => $filename));
			// 	}
			// }
			$update_config = OptionModel::where('id', '=', $get->id)->update($data);
			AppHelper::clearSiteConfigCache();
			if ($update_config) {
				return redirect('admincp/option')->with('msg', 'Update config successfully!');
			} else {
				return redirect('admincp/option')->with('msgerror', 'Update config unsuccessfully!');
			}
		}
	}

	public function get_change_pass() {
		return view('admincp.login.changepassword');
	}

	public function post_change_pass(Request $get) {
		if ($get->current_pass != NULL && $get->new_pass != NULL && strlen($get->new_pass) >= 6) {
			$user = \Session::get('logined');

			$data = array(
				'password' => md5($get->new_pass)
			);
			$update = UsersModel::where('id', '=', $user->id)->update($data);
			if ($update) {
				Session::forget('logined');
				return redirect('admincp/login');
			}
		}
		return redirect('admincp/change-password')->with('msg', 'Update password has failed! Please check again. Password must be at least 6 characters');
	}

	public function get_static_page() {
		$get_page = StaticPageModel::get();
		return view('admincp.login.pagelist')->with('static_page', $get_page);
	}

	public function get_add_static_page() {
		return view('admincp.login.page_add');
	}

	public function post_add_static_page(Request $get) {
		if ($get) {
			$titlename = $get->titlename;
			$content_page = $get->content_page;
			if ($titlename == NULL or $titlename == "") {
				return redirect('admincp/add-static-page')->with('msg', ' * Invalid Title only text  format!')->with('titlename', $titlename)->with('content_page', $content_page);
			}
			if (!preg_match("/^[a-zA-Z 0-9 ]*$/", $titlename)) {
				return redirect('admincp/add-static-page')->with('msg', ' * Invalid Title only text  format!')->with('titlename', $titlename)->with('content_page', $content_page);
			}
			if ($content_page == NULL or $content_page == "") {
				return redirect('admincp/add-static-page')->with('msg', ' * Please input not null here!')->with('titlename', $titlename)->with('content_page', $content_page);
			}
			$addpage = new StaticPageModel();
			$addpage->titlename = $titlename;
			$addpage->content_page = $content_page;
			$addpage->status = 1;
			if ($addpage->save()) {
				return redirect('admincp/static-page')->with('msg', 'Add page successfully!');
			}
		}
	}

	public function get_edit_static_page($id) {
		if ($id != NULL) {
			$checkid = StaticPageModel::find($id);
			if ($checkid != NULL) {
				return view('admincp.login.page_edit')->with('edit_page', $checkid);
			}
			return redirect('admincp/static-page')->with('msg', ' Request page not found!');
		}
		return redirect('admincp/static-page')->with('msg', ' Request page not found!');
	}

	public function post_edit_static_page(Request $get, $id) {
		if ($id != NULL) {
			$checkid = StaticPageModel::find($id);
			if ($checkid != NULL) {

				$titlename = $get->titlename;
				$content_page = $get->content_page;
				$status = $get->status;
				if ($titlename == NULL or $titlename == "") {
					return redirect('admincp/add-static-page')->with('msg', ' * Invalid Title only text  format!')->with('titlename', $titlename)->with('content_page', $content_page);
				}
				if (!preg_match("/^[a-zA-Z 0-9 ]*$/", $titlename)) {
					return redirect('admincp/add-static-page')->with('msg', ' * Invalid Title only text  format!')->with('titlename', $titlename)->with('content_page', $content_page);
				}
				if ($content_page == NULL or $content_page == "") {
					return redirect('admincp/add-static-page')->with('msg', ' * Please input not null here!')->with('titlename', $titlename)->with('content_page', $content_page);
				}

				$data = array(
					'titlename'    => $titlename,
					'content_page' => $content_page,
					'status'       => $status
				);
				$update = StaticPageModel::where('id', '=', $id)->update($data);
				PageHelper::clearStaticPageCache($id);
				if ($update) {
					return redirect('admincp/static-page')->with('msg', 'Updated successfully!');
				} else {
					return redirect('admincp/edit-static-page/' . $id . '');
				}
			}
			return redirect('admincp/static-page')->with('msg', ' Request page not found!');
		}
		return redirect('admincp/static-page')->with('msg', ' Request page not found!');
	}

	public function del_static_page($id) {
		if ( ($id != NULL or $id != "") and !in_array($id, ["1", "2", "3", "4", "5", "6", "7", "8", "9"]) ) {
			$checkPage = StaticPageModel::find($id);
			if (!empty($checkPage)) {
				if ($checkPage->delete()) {
					return redirect('admincp/static-page')->with('msg-success', 'Deleted Page successfully!');
				}
				return redirect('admincp/static-page')->with('msg-error', 'Deleted Page unsuccessfully!');
			}
			return redirect('admincp/static-page')->with('msg-error', 'Page not found!');
		}
		return redirect('admincp/static-page')->with('msg-error', 'Page not found!');
	}

	public function get_banip() {
		$get_list = BanIPModel::get();

		return view('admincp.login.ip_list')->with('ipban', $get_list);
	}

	public function get_add_banip() {
		return view('admincp.login.add_ip_ban')->with('title_pornstar', 'Banned IP Address Management');
	}

	/**
	 * add IP to black list
	 * TODO - should move to another controller
	 * @param Request $get
	 * @return type
	 */
	public function post_add_banip(Request $get) {
		if ($get) {
			$ip = $get->ip_ban;
			if (!filter_var($ip, FILTER_VALIDATE_IP) === false) {
				$addip = new BanIPModel();
				$addip->ip_ban = $ip;
				$addip->status = 1;

				if ($addip->save()) {
					return redirect('admincp/banip')->with('msg', 'Ban IP successfully');
				}
			}

			return redirect('admincp/add-banip')->with('msgerror', '' . $ip . ' is not a valid IP address');
		}
	}

	public function get_edit_banip($id) {
		if ($id != NULL) {
			$checkip = BanIPModel::find($id);
			if ($checkip != NULL) {
				return view('admincp.login.edit_ip_ban')->with('edit_ip', $checkip);
			}
			return redirect('admincp/banip')->with('msg', 'Request not fount!');
		}
		return redirect('admincp/banip')->with('msg', 'Request not fount!');
	}

	public function post_edit_banip(Request $get, $id) {
		if ($get) {
			$ip = $get->ip_ban;
			$status = $get->status;
			if (!filter_var($ip, FILTER_VALIDATE_IP) === false) {
				$update = BanIPModel::where('id', '=', $id)->update(array('ip_ban' => $ip, 'status' => $status));
				if ($update) {
					return redirect('admincp/banip')->with('msg', 'Update successfully!');
				}
			}
			return redirect('admincp/edit-banip')->with('msg', '' . $ip . ' is not a valid IP address');
		}
	}

	public function delete_IP($id) {
		if ($id != NULL) {
			$check = BanIPModel::find($id);
			if ($check != NULL) {
				$get_del = BanIPModel::where('id', '=', $id)->delete();
				if ($get_del) {
					return redirect('admincp/banip')->with('msg', 'Delete successfully!');
				}
			}
			return redirect('admincp/banip')->with('msgerror', 'Request not fount!');
		}
	}

	public function post_reset_password(Request $get) {
		if ($get) {
			$email = $get->email_forgot;
			$token = $get->_token;
			$checkadmin = UsersModel::where('email', '=', $email)->first();
			if ($checkadmin != NULL) {
				$string_pass = str_random(6);
				$update      = UsersModel::where('id', '=', $checkadmin->id)->update(array('password' => md5($string_pass)));
				if ($update) {
					$get_email_temp = EmailSettingModel::get_temp_admin_reset_password();
					$getoption      = AppHelper::getSiteConfig();
					$sendmail = Mail::send('admincp.mail.' . $get_email_temp->name_slug . '', array(
											'site_name' => $getoption->site_name,
											'site_phone' => $getoption->site_phone,
											'site_email' => $getoption->site_email,
											'newpassword' => $string_pass), function($message) use($email) {
										$message->to($email)->subject('Adult Streaming Website Administrator Change Password');
									});
					if ($sendmail) {
						return 1;
					}
				}
			}
			return 2;
		}
	}

	//payment setting
	public function get_payment_setting() {
		$config = AppHelper::getPaymentConfig();
		return view('admincp.payment.payment_setting')->with('config', $config);
	}

	public function post_payment_setting(Request $get) {
		if ($get) {
			$data = array(
					"clientAccnum" => $get->clientAccnum,
					"clientSubacc" => $get->clientSubacc,
					"formName" => $get->formName,
					"form_signle" => $get->form_signle,
					"language" => $get->language,
					"allowedTypes" => $get->allowedTypes,
					"allowedTypes_signle" => $get->allowedTypes_signle,
					"subscriptionTypeId" => $get->subscriptionTypeId,
					"subscriptionTypeId_signle" => $get->subscriptionTypeId_signle,
					"id" => $get->id
			);
			$update = PaymentConfigModel::where('id', '=', $get->id)->update($data);
			if ($update) {
				return redirect('admincp/payment-setting')->with('msg', 'Update successfully!');
			}
			return redirect('admincp/payment-setting')->with('msgerro', 'Update not Complete');
		}
	}

	public function get_list_member_payment() {
		return view('admincp.payment.payment_list')->with('payment');
	}

	public function post_list_member_payment(Request $post) {

		$start = $post->start;
		$length = $post->length;
		$col = $post->columns;
		$order = $post['order'][0];
		$orderby = $col[$order['column']]['data'];
		$orderby = $orderby==1?"title_name":($orderby==6?"expired":($orderby==3?"subscriptionInitialPrice":$orderby));
		$sortasc = $order['dir'];
		$criteria = "1=1";
		if($col[1]['search']['value']!=""){
			$keyword = $col[1]['search']['value'];
			$criteria = "IF(ISNULL(table_channel.title_name),table_video.title_name,table_channel.title_name) LIKE '%".$keyword."%'";
		}
		elseif($col[2]['search']['value']!=""){
			$keyword = $col[2]['search']['value'];
			$criteria = "CONCAT(table_users.firstname,' ', table_users.lastname) LIKE '%".$keyword."%'";
		}
		$recordsTotal = SubsriptionModel::count();
		$recordsFiltered = SubsriptionModel::select('id')
						->leftJoin('channel', 'channel.id', '=', 'subscription.channel_id')
						->leftJoin('users', 'users.id', '=', 'subscription.user_id')
						->leftJoin('video', 'video.string_Id', '=', 'subscription.video_id')
						->whereRaw($criteria)
						->count();
		$get_list = SubsriptionModel::select('subscription.*',
			DB::raw('(table_subscription.initialPeriod - DATEDIFF(now(),table_subscription.timestamp))  as expired'),
			DB::raw('IF(ISNULL(table_channel.title_name),table_video.title_name,table_channel.title_name) as title_name'), 'video.post_name as video_slug',
			DB::raw('CONCAT(table_users.firstname, " ", table_users.lastname) as customer_name'))
						->leftJoin('channel', 'channel.id', '=', 'subscription.channel_id')
						->leftJoin('users', 'users.id', '=', 'subscription.user_id')
						->leftJoin('video', 'video.string_Id', '=', 'subscription.video_id')
						->whereRaw($criteria)
						->orderBy($orderby, $sortasc)
						->limit($length)->offset($start)
						->get();
		$result = array(
			'data' => $get_list,
			'draw' => $post['draw'],
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered
		);

		return \Response::json($result);
	}


	public function getDeletePayment($id) {
		if (empty($id)) {
			return back()->with('msgerro', 'Request not found.');
		}
		$find = SubsriptionModel::find($id);
		if (empty($find)) {
			return back()->with('msgerro', 'Request not found.');
		}
		$member_id = $find->user_id;
		if ($member_id) {
			$user = MemberModel::whereUserId($member_id)->first();
		}
		if ($find->delete()) {
			if ($find->channel_id) {
				$channel_subscriber = ChanneSubscriberModel::whereChannelId($find->channel_id)->first();
				$channel_subscriber->member_Id = str_replace(array(',' . $user->id, $user->id), '', $channel_subscriber->member_Id);
				$channel_subscriber->save();
			}
			return back()->with('msg', 'Deleted successfully.');
		}
		return back()->with('msgerro', 'Request not found.');
	}

	public function get_header_link() {
		$get_header_link = HeaderModel::get();
		return view('admincp.option.header_list')->with('header_link', $get_header_link);
	}

	public function get_add_header_link() {
		return view('admincp.option.add_header_link');
	}

	public function post_add_header_link(Request $get) {
		if ($get) {
			$rules = [
					'title_name' => 'required|min:5',
					'content' => 'required|min:20|max:250',
					'link' => 'required|url',
			];
			$validator = Validator::make($get->all(), $rules);
			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}

			$title_name = $get->title_name;
			$content = $get->content;
			$link = $get->link;
			$status = $get->status;
			$add = new HeaderModel();
			$add->title_name = $title_name;
			$add->content = $content;
			$add->link = $link;
			$add->status = $status;
			if ($add->save()) {
				return redirect('admincp/header-link')->with('msg', 'Add successfully!');
			}
			return redirect('admincp/add-header-link')->with('msgerror', 'Add not complete!');
		}
	}

	public function get_edit_header_link($id) {
		if ($id != NULL) {
			$checkid = HeaderModel::find($id);
			if ($checkid != NULL) {
				return view('admincp.option.edit_header_link')->with('edit_header', $checkid);
			}
			return redirect('admincp/header-link')->with('msgerror', 'Request not found!');
		}
	}

	public function post_edit_header_link(Request $get, $id) {
		if ($get) {
			$data = array(
					"title_name" => $get->title_name,
					"content" => $get->content,
					"link" => $get->link,
					"status" => $get->status
			);
			$update = HeaderModel::where('id', '=', $id)->update($data);
			if ($update) {
				return redirect('admincp/header-link')->with('msg', 'update successfully!');
			}
			return redirect('admincp/edit-header-link/' . $id . '')->with('msg', 'update not complete!');
		}
	}

	public function get_delete_header_link($id) {
		if ($id != NULL) {
			$checkid = HeaderModel::find($id);
			if ($checkid != NULL) {
				$delete = HeaderModel::where('id', '=', $id)->delete();
				if ($delete) {
					return redirect('admincp/header-link')->with('msg', 'Delete successfully!');
				}
				return redirect('admincp/header-link/' . $id . '')->with('msg', 'Delete not complete!');
			}
			return redirect('admincp/header-link')->with('msgerror', 'Request not found!');
		}
	}

	public function get_conversion_config() {
		$get_conversion_config = ConversionModel::get_config();
		return view('admincp.option.conversion')->with('config', $get_conversion_config);
	}

	public function post_conversion_config(Request $get) {
		if ($get) {
			$data = array(
					"php_cli_path" => $get->php_cli_path,
					"mplayer_path" => $get->mplayer_path,
					"mencoder_path" => $get->mencoder_path,
					"ffmpeg_path" => $get->ffmpeg_path,
					"flvtool2_path" => $get->flvtool2_path,
					"mp4box_path" => $get->mp4box_path,
					"mediainfo_path" => $get->mediainfo_path,
					"yamdi_path" => $get->yamdi_path,
					"thumbnail_tool" => $get->thumbnail_tool,
					"meta_injection_tool" => $get->meta_injection_tool,
					"max_thumbnail_w" => $get->max_thumbnail_w,
					"max_thumbnail_h" => $get->max_thumbnail_h,
					"allowed_extension" => $get->allowed_extension
			);
			$update = ConversionModel::where('id', '=', $get->id)->update($data);
			if ($update) {
				return redirect('admincp/conversion-config')->with('msg', 'Update successfully!');
			}
			return redirect('admincp/conversion-config')->with('msgerror', 'Update not complete!');
		}
	}

	public function get_contact_list() {
		$contact = ContactModel::orderby('status', 'ASC')->get();
		return view('admincp.contact.list')->with('contact', $contact);
	}

	public function post_contact_list(Request $get, $id) {
		if ($get->reply != NULL) {
			$update = ContactModel::where('id', '=', $id)->update(array('reply' => $get->reply, 'status' => 2));
			if ($update) {
				$get_full_contact = ContactModel::find($id);
				$getoption = AppHelper::getSiteConfig();
				$sendmail = Mail::send('admincp.mail.reply_mail', array(
										'site_name' => $getoption->site_name,
										'email' => $get_full_contact->email,
										'content' => $get_full_contact->reply
												), function($message) use($get_full_contact, $getoption) {
									$message->to($get_full_contact->email)->subject('' . $getoption->site_name . ' Reply Contact');
								});
				if ($sendmail) {
					return redirect('admincp/contact')->with('msg-success', ' Your reply has been sent to ' . $get_full_contact->email . '');
				}
			}
		}
		return redirect('admincp/contact')->with('msgerror', 'Invalid reply content must be not blank!');
	}

	public function get_fqa_list() {
		$fqa = FQAModel::get();
		return view('admincp.contact.fqa_list')->with('fqa', $fqa);
	}

	public function get_add_fqa() {
		return view('admincp.contact.add_fqa');
	}

	public function post_add_fqa(Request $get) {
		$rules = array(
				'question' => 'required|min:20',
				'answer' => 'required|min:20',
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			$messages = $validator->messages();
			return Redirect()->back()->withInput()->withErrors($validator);
		}

		$add = new FQAModel();
		$add->question = $get->question;
		$add->answer = $get->answer;
		$add->status = $get->status;
		if ($add->save()) {
			return redirect('admincp/all-faq')->with('msg', 'Add new FQA successfully!');
		}
	}

	public function get_edit_fqa($id) {
		$check_ID = FQAModel::find($id);
		if ($check_ID != NULL) {
			return view('admincp.contact.edit_fqa')->with('edit', $check_ID);
		}
		return redirect('admincp/all-faq')->with('msgerror', 'Request not found!');
	}

	public function post_edit_fqa(Request $get, $id) {
		$rules = array(
				'question' => 'required',
				'answer' => 'required',
				'status' => 'required'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			$messages = $validator->messages();
			return Redirect()->back()->withInput()->withErrors($validator);
		}
		$update = FQAModel::find($id);
		$update->question = $get->question;
		$update->answer = $get->answer;
		$update->status = $get->status;
		if ($update->save()) {
			return redirect('admincp/all-faq')->with('msg', 'Update FQA successfully!');
		}
	}

	public function get_delete_fqa($id) {
		$check_ID = FQAModel::find($id);
		if ($check_ID != NULL) {
			if ($check_ID->delete()) {
				return redirect('admincp/all-faq')->with('msg', 'Delete FQA successfully!');
			}
		}
		return redirect('admincp/all-faq')->with('msgerror', 'Request not found!');
	}

	public function getChangeMail() {
		$user = \Session::get('logined')->email;
		return view('admincp.login.change_email', compact('user'));
	}

	public function postChangeMail(Request $get) {
		$rules = [
				'current_email' => 'required|email|exists:npt_users,email',
				'new_email' => 'required|email|unique:npt_users,email'
		];
		$validator = Validator::make($get->all(), $rules);
		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}

		$updateEmail = UsersModel::find(\Session::get('logined')->id);
		$updateEmail->email = $get->new_email;
		if ($updateEmail->save()) {
			\Session::put('logined', $updateEmail);
			return back()->with('msg', 'Updated email successfully');
		}
		return back('msgerro', 'Updated failed. please try again');
	}

	public function clearAllCache() {
		Cache::flush();
		return redirect('admincp')->with('msg', 'Clear all cache successfully');
	}

	public function get_tag() {
		return view('admincp.login.taglist');
	}

	public function post_list_tag(Request $post) {
		$start = $post->start;
		$length = $post->length;
		$col = $post->columns;
		$order = $post['order'][0];
		$orderby = $col[$order['column']]['data'];
		$orderby = $orderby==1?"tag":$orderby;
		$sortasc = $order['dir'];
		$criteria = "1=1";
		if($col[1]['search']['value']!="") {
			$keyword = $col[1]['search']['value'];
			$criteria = "table_tag.tag LIKE '%".$keyword."%'";
		}
		$recordsTotal = TagModel::count();
		$recordsFiltered = TagModel::select('id')
						->whereRaw($criteria)
						->count();
		$get_list = TagModel::select('tag.*')
						->whereRaw($criteria)
						->orderBy($orderby, $sortasc)
						->limit($length)->offset($start)
						->get();

		$result = array(
			'data' => $get_list,
			'draw' => $post['draw'],
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered
		);

		return \Response::json($result);
	}

	public function delete_all_tag($string_id) {
		$get = TagModel::whereRaw('id IN(' . $string_id . ')')->count();
		if ($get) {
			$deletedvideo = TagModel::whereRaw('id IN(' . $string_id . ')')->delete();
			if ($deletedvideo) {
				return redirect('admincp/tag')->with('msg', 'Deleted tags successfully!');
			}
			return redirect('admincp/tag')->with('msgerror', 'Request not found!');
		}
		return redirect('admincp/tag')->with('msgerror', 'Request not found!');
	}

	public function get_add_tag() {
		return view('admincp.login.tag_add');
	}

	public function post_add_tag(Request $get) {
		if ($get) {
			$tag = $get->tag;
			$content_page = $get->content_page;
			if ($tag == NULL or $tag == "") {
				return redirect('admincp/add-tag')->with('msg', ' * Invalid Tag only text  format!')->with('tag', $tag)->with('content_page', $content_page);
			}
			$addtag = new TagModel();
			$addtag->tag = $tag;
			$addtag->status = 1;
			if ($addtag->save()) {
				return redirect('admincp/tag')->with('msg', 'Add tag successfully!');
			}
		}
	}

	public function get_edit_tag($id) {
		if ($id != NULL) {
			$checkid = TagModel::find($id);
			if ($checkid != NULL) {
				return view('admincp.login.tag_edit')->with('edit_page', $checkid);
			}
			return redirect('admincp/tag')->with('msg', ' Request page not found!');
		}
		return redirect('admincp/tag')->with('msg', ' Request page not found!');
	}

	public function post_edit_tag(Request $get, $id) {
		if ($id != NULL) {
			$checkid = TagModel::find($id);
			if ($checkid != NULL) {

				$tag          = $get->tag;
				$status       = $get->status;
				if ($tag == NULL or $tag == "") {
					return redirect('admincp/add-tag')->with('msg', ' * Invalid Tag only text format!')->with('tag', $tag)->with('content_page', $content_page);
				}

				$data = array(
					'tag'    => $tag,
					'status' => $status
				);
				$update = TagModel::where('id', '=', $id)->update($data);
				AppHelper::clearSiteTagsCache();
				if ($update) {
					return redirect('admincp/tag')->with('msg', 'Update successfully!');
				} else {
					return redirect('admincp/edit-tag/' . $id . '');
				}
			}
			return redirect('admincp/tag')->with('msg', ' Request page not found!');
		}
		return redirect('admincp/tag')->with('msg', ' Request page not found!');
	}

	public function get_delete_tag($id) {
		if (empty($id)) {
			return back()->with('msgerror', 'Request not found.');
		}
		$find = TagModel::find($id);
		if (empty($find)) {
			return back()->with('msgerror', 'Request not found.');
		}
		if ($find->delete()) {
			return back()->with('msg', 'Deleted successfully.');
		}
		return back()->with('msgerror', 'Request not found.');
	}
}
