<?php
use App\Services\LanguageService as Languages;
use App\Http\Controllers\KeyHitech;
use App\Models\CategoriesModel;

use App\Helper\AppHelper;

//prevent blocked IP here
AppHelper::preventBlockedIp();

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// \Event::listen('illuminate.query', function ($query, $bindings, $time) {
// 	// echo'<pre>';
// 	// var_dump($query);
// 	// var_dump($bindings);
// 	// var_dump($time);
// 	// echo'</pre>';

// 	//code to save query logs in a file
// 	$logData = '';
// 	$queryStr =  $query .' - Time: '. $time;

// 	//remove all new lines
// 	$queryStr = trim(preg_replace( "/\r\n|\n/", "", $queryStr));
// 	$newArr = array(date('Y-m-d H:i:s'), $queryStr);
// 	$logData .= implode("\t",$newArr) . "\n";

// 	//write if any new data
// 	if($logData != ''){
// 		//open logs file if exists or create a new one
// 		$logFile = fopen(storage_path('logs/query_logs/'.date('Y-m-d').'_query.log'), 'a+');
// 		//write log to file
// 		fwrite($logFile, $logData);
// 		fclose($logFile);
// 	}
// });

Route::post('thank-you.html','login\LoginController@post_payment_thank_you_page');

Route::get('payment-complete.html','login\LoginController@complete_payment');

// $lang=str_replace('/','', getLang());
// dd(getLang());
// //dd($lang);
// if(!empty($lang)){
// 	App::setLocale($lang);
// 	View::share('lang',$lang);
// 	Route::get('/', function () {
// 		return redirect(str_replace('/','', getLang()));
// 	});
// }

Route::get('images/logo/{filename}', function ($filename) {
	$path = storage_path('/app/') . $filename;

	if(!File::exists($path)) abort(404);

	$file = File::get($path);
	$type = File::mimeType($path);

	$response = Response::make($file, 200);
	$response->header("Content-Type", $type);

	return $response;
});

Route::get('sitemap.xml', function () {
	$path = storage_path('/app/sitemap.xml');

	if(!File::exists($path)) abort(404);

	$file = File::get($path);
	$type = File::mimeType($path);

	$response = Response::make($file, 200);
	$response->header("Content-Type", $type);

	return $response;
});

$lang = LaravelLocalization::setLocale();
if($lang === NULL ){
	// $lang = '/' . config('app.locale') . '/';

	$lang = Languages::getDefaultLanguage();

	LaravelLocalization::setLocale($lang);

	Route::get('/', function () {
		return redirect(str_replace('/','', LaravelLocalization::setLocale()));
	});
}
View::share('lang',$lang);

// dd(LaravelLocalization::setLocale());
Route::group([
	'prefix' => LaravelLocalization::setLocale(),
	'middleware' => [ 'localize' ]
], function($lang) {

	Route::get('show-payment','login\LoginController@get_channel_subscription');

	Route::post('player-relate-video','video\VideoController@postVideoRelate');

	Route::get('load-ads','video\VideoController@getLoadAds');

	Route::get('/','home\HomeController@get_indexnew');

	Route::get('/views','home\HomeController@get_order_views');

	Route::get('/duration','home\HomeController@get_order_duration');

	Route::get('/rating-video','home\HomeController@get_order_rating');

	Route::get('/date','home\HomeController@get_order_date');

	Route::post('/login.html','login\LoginController@signin');

	Route::get('/login.html','login\LoginController@get_login');

	Route::get('/logout.html','login\LoginController@logout');

	Route::post('/signup.html','login\LoginController@signup');

	Route::get('register.html&action=active&token={token}','login\LoginController@get_confrim_active');

	Route::get('/member-proflie.html?action=upload','login\LoginController@get_memberprofile');

	Route::get('/member-proflie.html','login\LoginController@get_memberprofile');

	Route::get('/member-collections.html','login\LoginController@get_membercollections');

	Route::get('/videos/{id}/remove-video-fav.html','login\LoginController@remove_videoFav');

	Route::get('/channel/{id}/remove-channel-sub.html','login\LoginController@remove_channelSub');

	Route::get('/member-collections.html&date={date}&keyword={keyword}','login\LoginController@get_membercollections');

	Route::post('/member-collections.html','login\LoginController@post_searchcollections');

	Route::get('/member-subscribe.html','login\LoginController@get_membersubscribe');

	Route::get('/member-overview.html','login\LoginController@get_member');

	Route::post('/upload-member-avatar', 'login\LoginController@uploadAvatar');

	Route::get('/member-change-password.html','login\LoginController@get_changepassword');

	Route::get('/member-payment-history.html','login\LoginController@get_member_payment_hitory');

	Route::get('/change-channel.html','login\LoginController@get_channel');

	Route::get('/channel-dashboard.html','login\LoginController@get_channel_dashboard');

	Route::post('/channel-edit.html','login\LoginController@edit_channel');

	Route::get('/message-member.html','login\LoginController@get_message');

	Route::get('/member-friend.html','login\LoginController@get_friend');

	Route::get('/member-friend-update-status/{id}.html','login\LoginController@get_friend_status');

	Route::post('/send-reply.html','login\LoginController@post_reply_message');

	Route::get('/member-edit.html','login\LoginController@get_viewedit');

	Route::post('/member-edit.html','login\LoginController@post_viewedit');

	Route::get('/delete-message/{postid}.html','login\LoginController@post_deletemessage');

	Route::post('/member-change-password.html','login\LoginController@post_changepassword');

	Route::get('/profile/{id}/{name}.html','login\LoginController@get_view_member_profile');

	Route::get('/send-message-to-member.html&member={id}','login\LoginController@get_send_message');

	Route::post('/send-message-to-member.html&member={id}','login\LoginController@post_send_message');

	Route::post('/add-friend.html','login\LoginController@post_add_friend');

	Route::post('/un-friend.html','login\LoginController@post_un_friend');

	Route::get('friend-memner&id={id}','login\LoginController@get_friend_member');

	Route::get('video.html&action={action}&catid={catid}','video\VideoController@get_action_video');

	Route::get('video.html&action={action}&date={date}&time={time}','video\VideoController@get_action_filter');

	Route::get('video.html&action={action}','video\VideoController@get_action_video');

	Route::get('/watch/{string_Id}/{name}.html','video\VideoController@get_view');

	Route::post('/comment.html','video\VideoController@post_comment');

	Route::get('/comment.html','video\VideoController@get_comment');

	Route::get('/loadmore/{string_Id}.html','video\VideoController@loadmore');

	Route::get('/{name}.{string_Id}/download.html','video\VideoController@get_download');

	Route::get('/{name}.{string_Id}/favorite.html','video\VideoController@get_favorite');

	Route::get('/{name}.{string_Id}/subscribe.html','video\VideoController@get_subscribe');

	Route::get('/search.html&keyword={keyword}&sort={sort}&date={date}&duration={time}','video\VideoController@get_search_filter');

	Route::get('/search.html','video\VideoController@get_search_video');

	Route::get('/categories.html','categories\CategoriesController@get_categories');

	Route::get('/categories/{id}.{name}.html','categories\CategoriesController@get_one_categories');

	Route::get('country/{id}/{name}','categories\CategoriesController@get_video_country');

	Route::get('categories/{id}.{name}.html&sort={action}&time={time}','categories\CategoriesController@get_one_category_filter');

	Route::get('/channel.html','channel\ChannelController@get_channel');

	Route::get('/channel-recently.html','channel\ChannelController@channel_recently');

	Route::get('/channel-subscriber.html','channel\ChannelController@channel_subscriber');

	Route::get('/channel-popular.html','channel\ChannelController@channel_popular');

	Route::get('/channel.html&type={id}&video={string_id}','channel\ChannelController@get_view_video_channel');

	Route::get('/channel/{id}/{name}','channel\ChannelController@get_channel_video');

	Route::get('/top-rate.html&date={date}&time={time}','toprate\TopRateController@get_top_rate_filter');

	Route::get('/top-rate.html','toprate\TopRateController@get_videotoprate');

	Route::get('/porn-stars.html', 'pornstar\PornStarController@get_pornstar');

	Route::get('/pornstars/{id}/{name}','pornstar\PornStarController@get_pornstar_video');

	Route::get('/pornstars/{id}/{name}/video','pornstar\PornStarController@get_ajx_pornstar_video');

	Route::get('/pornstars/{id}/{name}/photo','pornstar\PornStarController@get_ajx_pornstar_photo');

	Route::get('/most-view.html&date={date}&time={time}','mostview\MostViewController@get_videomostview_find');

	Route::get('/most-view.html','mostview\MostViewController@get_videomostview');

	Route::get('/loadingmsg&msgfrom={id}','login\LoginController@get_msg_list');

	Route::get('/loadingvideo&member={id}','login\LoginController@get_video_profile');

	Route::get('/loadingsubscribe&member={id}','login\LoginController@get_subscribe_profile');

	Route::post('forgot-password.html','login\LoginController@post_mail_forgot');

	Route::post('report-user','login\LoginController@post_report_user');

	Route::post('block-user','login\LoginController@post_block_user');

	Route::get('filter-channel.html&key={key}','channel\ChannelController@get_filter');

	Route::get('filter-porn-stars.html&key={key}','pornstar\PornStarController@get_filter');

	Route::get('/lib/billing.php',function(){
		redirect('/');
	});

	Route::get('503','login\LoginController@get_503');

	Route::get('subscribe&chanel={channelid}','channel\ChannelController@get_subscriber');

	Route::post('private-msg','login\LoginController@post_private_msg');

	Route::post('profile-comment','login\LoginController@post_comment');

	Route::get('rating&type={vote}&id={string_id}','video\VideoController@get_rating');

	Route::get('pornstar_rating&type={vote}&id={string_id}','pornstar\PornStarController@get_rating');

	Route::post('send-share-video','video\VideoController@post_send_share_video');

	Route::get('upload.html','video\VideoController@get_video_upload');

	Route::post('upload.html','video\VideoController@post_video_upload');

	// get list video premium hd
	Route::get('premium-hd.html','premium\PremiumController@index');

	Route::get('premium-hd.html&sort={sort}','premium\PremiumController@get_sort');

	Route::get('embedframe/{id}','video\VideoController@get_video_embed');

	Route::get('upload-video.html&action={action}','video\VideoController@get_video_upload_temp');

	Route::post('upload-video.html&action={action}','video\VideoController@post_member_video_upload');

	Route::post('member-auto-upload-video','video\VideoController@member_auto_upload_video');

	Route::get('show-video-upload.html','video\VideoController@get_video_member_upload');

	Route::get('member-delete-video&id={string}','video\VideoController@get_member_delete_video');

	Route::get('member-edit-thumbnail-video&id={string}','video\VideoController@get_member_edit_thumbnail');

	Route::post('member-edit-thumbnail-video&id={string}','Video\VideoController@post_member_edit_thumbnail');

	Route::get('save-thumb&t={data}','video\VideoController@get_save_thumb_chose');

	Route::get('information/{id}','video\VideoController@show_static_page');
	//channel-user
	Route::post('channel-regist','channel\ChannelController@get_user_regist_channel');

	Route::get('infomation-fqa','login\LoginController@get_FQA');

	Route::get('/sitemap','login\LoginController@get_sitemap');

	Route::get('/contact','login\LoginController@get_contact');

	Route::post('/contact','login\LoginController@post_contact');

	Route::post('/add-view','video\VideoController@post_add_view');

});// End group Lang.

///Admin Routers
Route::group(array('prefix'=>'admincp'),function(){
	Route::get('logout','admincp\LoginController@getLogout');

	Route::get('login',array(
			'as'	=>'login',
			'uses'	=>'admincp\LoginController@getLogin',
			'middleware'=>'HasSession'));

	Route::get('/',array(
				'as'	=>'admincp',
				'uses'	=>"admincp\DashboardController@get_dashboard",
				'middleware'=>'checkLogin'));

	Route::post('login','admincp\LoginController@postLogin');

	Route::post('admin-rest-password','admincp\LoginController@post_reset_password');

	Route::get('categories',array(
				'uses'=>'admincp\CategoriesController@get_categories',
				'middleware'=>'checkLogin'
		));

	Route::get('add-categories',array(
		'uses'=>'admincp\CategoriesController@get_addcategories',
		'middleware'=>'checkLogin'
		));

	Route::post('add-categories',array(
		'uses'=>'admincp\CategoriesController@post_addcategories',
		'middleware'=>'checkLogin'
		));

	Route::get('edit-categories/{id}',array(
		'uses'=>'admincp\CategoriesController@get_editcategories',
		'middleware'=>'checkLogin'
		));

	Route::post('edit-categories/{id}',array(
		'uses'=>'admincp\CategoriesController@post_editcategories',
		'middleware'=>'checkLogin'
		));

	Route::get('delete-categories/{id}',array(
		'uses'=>'admincp\CategoriesController@get_deletecategories',
		'middleware'=>'checkLogin'
		));

	Route::post('category-manage',array(
		'uses'=>'admincp\CategoriesController@post_list_category',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-all-categories-check/{string_id}',array(
		'uses'=>'admincp\CategoriesController@delete_all_category',
		'middleware' =>'checkLogin'
		));

	Route::get('user',array(
		'uses'=>'admincp\MembersController@get_user',
		'middleware'=>'checkLogin'
		));

	Route::get('edit-user/{id}',array(
		'uses' =>'admincp\MembersController@get_edituser',
		'middleware' =>'checkLogin'
		));

	Route::post('edit-user/{id}',array(
		'uses' =>'admincp\MembersController@post_edituser',
		'middleware' =>'checkLogin'
		));

	Route::get('member',array(
		'uses'=>'admincp\MembersController@get_member',
		'middleware'=>'checkLogin'
		));

	Route::get('edit-member&id={id}',array(
		'uses'=>'admincp\MembersController@get_editmember',
		'middleware'=>'checkLogin'
		));

	Route::post('edit-member&id={id}',array(
		'uses'=>'admincp\MembersController@post_editmember',
		'middleware'=>'checkLogin'
		));

	Route::get('add-member',array(
		'uses'=>'admincp\MembersController@get_addmember',
		'middleware'=>'checkLogin'
		));

	Route::post('add-member',array(
		'uses'=>'admincp\MembersController@post_addmember',
		'middleware'=>'checkLogin'
		));

	Route::get('delete-user/{id}',array(
		'uses'=>'admincp\MembersController@get_delete_user',
		'middleware'=>'checkLogin'
		));

	Route::get('block/id={id}',array(
		'uses'=>'admincp\MembersController@set_block_member',
		'middleware'=>'checkLogin'
		));

	Route::get('approve/id={id}',array(
		'uses'=>'admincp\MembersController@set_approve_member',
		'middleware'=>'checkLogin'
		));

	Route::get('add-video',array(
		'uses' =>'admincp\VideoController@get_addvideo',
		'middleware' =>'checkLogin'
		));

	Route::post('add-video',array(
		'uses'=>'admincp\VideoController@post_addvideo',
		'middleware' =>'checkLogin'
		));

	Route::get('video',array(
		'uses' =>'admincp\VideoController@get_video',
		'middleware' =>'checkLogin'
		));
	Route::post('video-manage',array(
		'uses'=>'admincp\VideoController@post_list_video',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-video/{string_Id}',array(
		'uses' =>'admincp\VideoController@get_deletevideo',
		'middleware' => 'checkLogin'
		));

	Route::get('edit-video/{string_Id}',array(
		'uses' =>'admincp\VideoController@get_editvideo',
		'middleware'=>'checkLogin'
		));

	Route::post('edit-video/{string_Id}',array(
		'uses' =>'admincp\VideoController@post_editvideo',
		'middleware' =>'checkLogin'
		));

	Route::get('edit-channel/{id}',array(
		'uses' =>'admincp\ChannelController@get_editchannel',
		'middleware' =>'checkLogin'
		));

	Route::post('edit-channel/{id}',array(
		'uses' =>'admincp\ChannelController@post_editchannel',
		'middleware' =>'checkLogin'
		));

	Route::get('channel',array(
		'uses' =>'admincp\ChannelController@get_channel',
		'middleware' =>'checkLogin'
		));

	Route::get('channel-subscriber',array(
		'uses' =>'admincp\ChannelController@get_channelsubscriber',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-channel/{id}',array(
		'uses' =>'admincp\ChannelController@get_deletechannel',
		'middleware' => 'checkLogin'
		));

	Route::delete('channel/delete-ids',array(
		'uses' =>'admincp\ChannelController@removeIds',
		'middleware' => 'checkLogin'
		));

	Route::get('add-channel',array(
		'uses' =>'admincp\ChannelController@get_addchannel',
		'middleware' => 'checkLogin'
		));

	Route::post('add-channel',array(
		'uses' =>'admincp\ChannelController@post_addchannel',
		'middleware' => 'checkLogin'
		));

	Route::get('approve-member-register-channel&id={id}',array(
		'uses' =>'admincp\ChannelController@get_approve_register',
		'middleware' => 'checkLogin'
		));

	Route::get('edit-pornstar/{id}',array(
		'uses' =>'admincp\PornStarController@get_editpornstar',
		'middleware' => 'checkLogin'
		));

	Route::post('edit-pornstar/{id}',array(
		'uses' =>'admincp\PornStarController@post_editpornstar',
		'middleware' =>'checkLogin'
		));

	Route::get('pornstar',array(
		'uses' =>'admincp\PornStarController@get_pornstar',
		'middleware' => 'checkLogin'
		));

	Route::get('pornstar-subscriber',array(
		'uses' =>'admincp\PornStarController@get_pornstarsubscriber',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-pornstar/{id}',array(
		'uses' =>'admincp\PornStarController@get_deletepornstar',
		'middleware'=>	'checkLogin'
		));
	Route::delete('porn-stars/delete-ids',array(
		'uses' =>'admincp\PornStarController@deletePornStarsIds',
		'middleware'=>	'checkLogin'
		));

	Route::get('add-pornstar',array(
		'uses' =>'admincp\PornStarController@get_addpornstar',
		'middleware'=> 'checkLogin'
		));

	Route::post('add-pornstar',array(
		'uses' =>'admincp\PornStarController@post_addpornstar',
		'middleware' => 'checkLogin'
		));

	Route::get('photo-pornstar/{id}',array(
		'uses' =>'admincp\PornStarController@get_pornstar_photo_allbum',
		'middleware'=> 'checkLogin'
		));

	Route::get('add-photo/{id}',array(
		'uses' =>'admincp\PornStarController@get_add_photo_allbum',
		'middleware'=> 'checkLogin'
		));

	Route::post('add-photo/{id}',array(
		'uses' =>'admincp\PornStarController@post_add_photo_allbum',
		'middleware' => 'checkLogin'
		));

	Route::get('video-comment',array(
		'uses'=>'admincp\VideoController@get_comment',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-video-comment/{string_Id}',array(
		'uses'=>'admincp\VideoController@get_delete_comment',
		'middleware' => 'checkLogin'
		));

	Route::get('edit-video-comment/{string_Id}',array(
		'uses'=>'admincp\VideoController@get_edit_comment',
		'middleware' => 'checkLogin'
		));
	Route::get('report-comment&id={listid}',array(
		'uses'=>'admincp\VideoController@get_report',
		'middleware' => 'checkLogin'
		));

	Route::get('admin-reply-comment/{listid}',array(
		'uses'=>'admincp\VideoController@get_admin_reply',
		'middleware' => 'checkLogin'
		));

	Route::post('admin-reply-comment/{listid}',array(
		'uses'=>'admincp\VideoController@post_admin_reply',
		'middleware' => 'checkLogin'
		));

	Route::get('option',array(
		'uses'=>'admincp\LoginController@get_option',
		'middleware' => 'checkLogin'
		));

	Route::post('option',array(
		'uses'=>'admincp\LoginController@post_option',
		'middleware' => 'checkLogin'
		));

	Route::post('edit-video-comment/{string_Id}',array(
		'uses'=>'admincp\VideoController@post_edit_comment',
		'middleware' => 'checkLogin'
		));

	//text Ads
	Route::get('ads-text-video',array(
		'uses'=>'admincp\VideoSettingController@get_text_ads',
		'middleware' => 'checkLogin'
		));

	Route::get('add-video-text-ads',array(
		'uses'=>'admincp\VideoSettingController@get_add_text_ads',
		'middleware' => 'checkLogin'
		));

	Route::post('add-video-text-ads',array(
		'uses'=>'admincp\VideoSettingController@post_add_text_ads',
		'middleware' => 'checkLogin'
		));

	Route::get('edit-video-text-ads&is={id}',array(
		'uses'=>'admincp\VideoSettingController@get_edit_text_ads',
		'middleware' => 'checkLogin'
		));

	Route::post('edit-video-text-ads&is={id}',array(
		'uses'=>'admincp\VideoSettingController@post_edit_text_ads',
		'middleware' => 'checkLogin'
		));

	Route::get('del-text-ads&is={id}',array(
		'uses'=>'admincp\VideoSettingController@del_text_ads',
		'middleware' => 'checkLogin'
		));

	Route::post('text-ads-manage',array(
		'uses'=>'admincp\VideoSettingController@post_list_text_ads',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-all-text-ads-check/{string_id}',array(
		'uses'=>'admincp\VideoSettingController@delete_all_text_ads',
		'middleware' =>'checkLogin'
		));

	//standard Ads
	Route::get('ads-standard',array(
		'uses'=>'admincp\VideoController@get_standard_ads',
		'middleware' => 'checkLogin'
		));

	Route::get('add-standard-ads',array(
		'uses'=>'admincp\VideoController@get_add_standard_ads',
		'middleware' => 'checkLogin'
		));

	Route::post('add-standard-ads',array(
		'uses'=>'admincp\VideoController@post_add_standard_ads',
		'middleware' => 'checkLogin'
		));

	Route::get('edit-standard-ads&is={id}',array(
		'uses'=>'admincp\VideoController@get_edit_standard_ads',
		'middleware' => 'checkLogin'
		));

	Route::post('edit-standard-ads&is={id}',array(
		'uses'=>'admincp\VideoController@post_edit_standard_ads',
		'middleware' => 'checkLogin'
		));

	Route::get('del-standard-ads&is={id}',array(
		'uses'=>'admincp\VideoController@del_standard_ads',
		'middleware' => 'checkLogin'
		));

	Route::post('standard-ads-manage',array(
		'uses'=>'admincp\VideoController@post_list_standard_ads',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-all-standard-ads-check/{string_id}',array(
		'uses'=>'admincp\VideoController@delete_all_standard_ads',
		'middleware' =>'checkLogin'
		));

	//video Ads
	Route::get('ads-video',array(
		'uses'=>'admincp\VideoSettingController@get_video_ads',
		'middleware' => 'checkLogin'
		));

	Route::get('in-player-media-ads',array(
		'uses'=>'admincp\VideoSettingController@get_in_player_media_ads',
		'middleware' => 'checkLogin'
		));

	Route::post('in-player-media-ads',array(
		'uses'=>'admincp\VideoSettingController@post_in_player_media_ads',
		'middleware' => 'checkLogin'
		));

	Route::get('edit_in-player-media-ads/{id}',array(
		'uses'=>'admincp\VideoSettingController@get_edit_video_ads',
		'middleware' => 'checkLogin'
		));

	Route::post('edit_in-player-media-ads/{id}',array(
		'uses'=>'admincp\VideoSettingController@post_edit_video_ads',
		'middleware' => 'checkLogin'
		));

	Route::get('delete_in-player-media-ads/{id}',array(
		'uses'=>'admincp\VideoSettingController@del_video_ads',
		'middleware' => 'checkLogin'
		));

	Route::post('video-ads-manage',array(
		'uses'=>'admincp\VideoSettingController@post_list_video_ads',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-all-video-ads-check/{string_id}',array(
		'uses'=>'admincp\VideoSettingController@delete_all_video_ads',
		'middleware' =>'checkLogin'
		));

	//End Video Ads
	Route::get('change-password',array(
		'uses'=>'admincp\LoginController@get_change_pass',
		'middleware' => 'checkLogin'
		));

	Route::post('change-password',array(
		'uses'=>'admincp\LoginController@post_change_pass',
		'middleware' => 'checkLogin'
		));

	Route::get('static-page',array(
		'uses'=>'admincp\LoginController@get_static_page',
		'middleware' => 'checkLogin'
		));

	Route::get('add-static-page',array(
		'uses'=>'admincp\LoginController@get_add_static_page',
		'middleware' => 'checkLogin'
		));

	Route::post('add-static-page',array(
		'uses'=>'admincp\LoginController@post_add_static_page',
		'middleware' => 'checkLogin'
		));

	Route::get('edit-static-page/{id}',array(
		'uses'=>'admincp\LoginController@get_edit_static_page',
		'middleware' => 'checkLogin'
		));

	Route::post('edit-static-page/{id}',array(
		'uses'=>'admincp\LoginController@post_edit_static_page',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-static-page/{id}',array(
		'uses'=>'admincp\LoginController@del_static_page',
		'middleware' => 'checkLogin'
		));

	//banip
	Route::get('banip',array(
		'uses'=>'admincp\LoginController@get_banip',
		'middleware' => 'checkLogin'
		));

	Route::get('add-banip',array(
		'uses'=>'admincp\LoginController@get_add_banip',
		'middleware' => 'checkLogin'
		));

	Route::post('add-banip',array(
		'uses'=>'admincp\LoginController@post_add_banip',
		'middleware' => 'checkLogin'
		));

	Route::get('edit-banip/{id}',array(
		'uses'=>'admincp\LoginController@get_edit_banip',
		'middleware' => 'checkLogin'
		));

	Route::post('edit-banip/{id}',array(
		'uses'=>'admincp\LoginController@post_edit_banip',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-banip/{id}',array(
		'uses'=>'admincp\LoginController@delete_ip',
		'middleware' => 'checkLogin'
		));

	Route::get('member-video-upload',array(
		'uses'=>'admincp\MembersController@get_member_video_upload',
		'middleware' => 'checkLogin'
		));

	Route::get('approve&id={id}',array(
		'uses'=>'admincp\MembersController@set_approve',
		'middleware' => 'checkLogin'
		));

	Route::get('block&id={id}',array(
		'uses'=>'admincp\MembersController@set_block',
		'middleware' => 'checkLogin'
		));

	Route::post('auto-upload-video',array(
		'uses'=>'admincp\VideoController@auto_upload_video',
		'middleware' => 'checkLogin'
		));

	Route::post('pornstar-upload-allbum',array(
		'uses'=>'admincp\PornStarController@auto_upload_allbum',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-photo/{id}',array(
		'uses'=>'admincp\PornStarController@get_photo_delete',
		'middleware' => 'checkLogin'
		));

	Route::get('grab-video',array(
		'uses'=>'admincp\VideoController@get_grab_video',
		'middleware' => 'checkLogin'
		));

	Route::post('grab-video',array(
		'uses'=>'admincp\VideoController@post_url_grab_video',
		'middleware' => 'checkLogin'
		));

	Route::get('payment-setting',array(
		'uses'=>'admincp\LoginController@get_payment_setting',
		'middleware' => 'checkLogin'
		));

	Route::post('payment-setting',array(
		'uses'=>'admincp\LoginController@post_payment_setting',
		'middleware' => 'checkLogin'
		));

	Route::get('payment-manage',array(
		'uses'=>'admincp\LoginController@get_list_member_payment',
		'middleware' => 'checkLogin'
		));
	Route::post('payment-manage',array(
		'uses'=>'admincp\LoginController@post_list_member_payment',
		'middleware' => 'checkLogin'
		));

	Route::post('save-video',array(
		'uses'=>'admincp\VideoController@save_video',
		'middleware' => 'checkLogin'
		));

	Route::get('private-message',array(
		'uses'=>'admincp\VideoController@get_list_private_message',
		'middleware' => 'checkLogin'
		));

	Route::get('reply-message/{id}',array(
		'uses'=>'admincp\VideoController@get_relay_message',
		'middleware' => 'checkLogin'
		));

	Route::post('reply-message/{id}',array(
		'uses'=>'admincp\VideoController@post_relay_message',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-message/{id}',array(
		'uses'=>'admincp\VideoController@del_message',
		'middleware' => 'checkLogin'
		));

	Route::get('header-link',array(
		'uses'=>'admincp\LoginController@get_header_link',
		'middleware' => 'checkLogin'
		));

	Route::get('add-header-link',array(
		'uses'=>'admincp\LoginController@get_add_header_link',
		'middleware' => 'checkLogin'
		));

	Route::post('add-header-link',array(
		'uses'=>'admincp\LoginController@post_add_header_link',
		'middleware' => 'checkLogin'
		));

	Route::get('edit-header-link/{id}',array(
		'uses'=>'admincp\LoginController@get_edit_header_link',
		'middleware' => 'checkLogin'
		));

	Route::post('edit-header-link/{id}',array(
		'uses'=>'admincp\LoginController@post_edit_header_link',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-header-link/{id}',array(
		'uses'=>'admincp\LoginController@get_delete_header_link',
		'middleware' => 'checkLogin'
		));

	Route::get('general-setting',array(
		'uses'=>'admincp\LoginController@get_general_setting',
		'middleware' => 'checkLogin'
		));

	Route::get('conversion-config',array(
		'uses'=>'admincp\LoginController@get_conversion_config',
		'middleware' => 'checkLogin'
		));

	Route::post('conversion-config',array(
		'uses'=>'admincp\LoginController@post_conversion_config',
		'middleware' => 'checkLogin'
		));

	Route::get('video-setting',array(
		'uses'=>'admincp\VideoSettingController@get_setting',
		'middleware' => 'checkLogin'
		));

	Route::post('video-setting',array(
		'uses'=>'admincp\VideoSettingController@post_setting',
		'middleware' => 'checkLogin'
		));
	Route::get('theme-setting',array(
		'uses'=>'admincp\SettingController@get_setting',
		'middleware' => 'checkLogin'
		));

	Route::post('theme-setting',array(
		'uses'=>'admincp\SettingController@post_setting',
		'middleware' => 'checkLogin'
		));

	Route::get('dowload-video-manage',array(
		'uses'=>'admincp\DownloaderController@index',
		'middleware' => 'checkLogin'
		));

	Route::get('dowload-video-add-view',array(
		'uses'=>'admincp\DownloaderController@add_view',
		'middleware' => 'checkLogin'
		));

	Route::post('dowload-video-add',array(
		'uses'=>'admincp\DownloaderController@add',
		'middleware' => 'checkLogin'
		));

	Route::get('email-templete',array(
		'uses'=>'admincp\VideoSettingController@get_email_templete',
		'middleware' => 'checkLogin'
		));

	Route::get('add-email-templete',array(
		'uses'=>'admincp\VideoSettingController@get_add_email_templete',
		'middleware' => 'checkLogin'
		));

	Route::post('add-email-templete',array(
		'uses'=>'admincp\VideoSettingController@post_add_email_templete',
		'middleware' => 'checkLogin'
		));

	Route::get('edit-email-templete&id={id}',array(
		'uses'=>'admincp\VideoSettingController@get_edit_email_templete',
		'middleware' => 'checkLogin'
		));

	Route::post('edit-email-templete&id={id}',array(
		'uses'=>'admincp\VideoSettingController@post_edit_email_templete',
		'middleware' => 'checkLogin'
		));

	Route::get('del-email-templete&id={id}',array(
		'uses'=>'admincp\VideoSettingController@get_del_email_templete',
		'middleware' => 'checkLogin'
		));

	Route::get('email-setting',array(
		'uses'=>'admincp\VideoSettingController@get_email_setting',
		'middleware' => 'checkLogin'
		));

	Route::post('email-setting',array(
		'uses'=>'admincp\VideoSettingController@post_email_setting',
		'middleware' => 'checkLogin'
		));

	Route::get('user/delete/{listid}',array(
		'uses'=>'admincp\MembersController@get_delete_member_list',
		'middleware' => 'checkLogin'
		));

	Route::get('contact',array(
		'uses'=>'admincp\LoginController@get_contact_list',
		'middleware' => 'checkLogin'
		));

	Route::post('contact&action=reply&id={id}',array(
		'uses'=>'admincp\LoginController@post_contact_list',
		'middleware' => 'checkLogin'
		));

	Route::get('all-faq',array(
		'uses'=>'admincp\LoginController@get_fqa_list',
		'middleware' => 'checkLogin'
		));

	Route::get('add-faq',array(
		'uses'=>'admincp\LoginController@get_add_fqa',
		'middleware' => 'checkLogin'
		));

	Route::post('add-faq',array(
		'uses'=>'admincp\LoginController@post_add_fqa',
		'middleware' => 'checkLogin'
		));

	Route::get('edit-faq/{id}',array(
		'uses'=>'admincp\LoginController@get_edit_fqa',
		'middleware' => 'checkLogin'
		));

	Route::post('edit-faq/{id}',array(
		'uses'=>'admincp\LoginController@post_edit_fqa',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-faq/{id}',array(
		'uses'=>'admincp\LoginController@get_delete_fqa',
		'middleware' => 'checkLogin'
		));

	Route::get('member-list-subscribe/{id}',array(
		'uses'=>'admincp\ChannelController@get_member_list_subscribe',
		'middleware' => 'checkLogin'
		));

	Route::get('language/setting',array(
		'uses'=>'admincp\LanguageController@getLanguageSetting',
		'middleware' => 'checkLogin'
		));

	Route::post('language/setting',array(
		'uses'=>'admincp\LanguageController@postLanguageSetting',
		'middleware' => 'checkLogin'
		));

	Route::get('language/all',array(
		'uses'=>'admincp\LanguageController@getAllLanguage',
		'middleware' => 'checkLogin'
		));

	Route::get('language/add',array(
		'uses'=>'admincp\LanguageController@getAddLanguage',
		'middleware' => 'checkLogin'
		));

	Route::post('language/add',array(
		'uses'=>'admincp\LanguageController@postAddLanguage',
		'middleware' => 'checkLogin'
		));

	Route::get('language/edit/{id}',array(
		'uses'=>'admincp\LanguageController@getEditLanguage',
		'middleware' => 'checkLogin'
		));

	Route::post('language/update/{id}',array(
		'uses'=>'admincp\LanguageController@getUpdateLanguage',
		'middleware' => 'checkLogin'
		));

	Route::get('language/delete/{id}',array(
		'uses'=>'admincp\LanguageController@getDeleteLanguage',
		'middleware' => 'checkLogin'
		));
	Route::get('language/stranlate/{id}',array(
		'uses'=>'admincp\LanguageController@getStranlateLanguage',
		'middleware' => 'checkLogin'
		));

	Route::get('language/stranlate/{id}/add',array(
		'uses'=>'admincp\LanguageController@addNewTranslation',
		'middleware' => 'checkLogin'
		));

	Route::post('language/stranlate/{id}/add',array(
		'uses'=>'admincp\LanguageController@postNewTranslation',
		'middleware' => 'checkLogin'
		));

	Route::post('language/stranlate/{id}',array(
		'uses'=>'admincp\LanguageController@postStranlateLanguage',
		'middleware' => 'checkLogin'
		));

	Route::get('add-multi-video',array(
		'uses'=>'admincp\VideoController@getBulkVideo',
		'middleware' => 'checkLogin'
		));

	Route::post('add-multi-video',array(
		'uses'=>'admincp\VideoController@postBulkVideo',
		'middleware' => 'checkLogin'
		));

	Route::post('auto-bulk-upload',array(
		'uses'=>'admincp\VideoController@postBulkAutoUpload',
		'middleware' => 'checkLogin'
		));

	Route::post('import-video',array(
		'uses'=>'admincp\DownloaderController@post_import_video',
		'middleware' => 'checkLogin'
		));

	//duplicates list video.
	Route::get('duplicates-video',array(
		'uses'=>'admincp\VideoController@get_duplicates_video',
		'middleware' => 'checkLogin'
		));

	Route::get('duplicates-list-video',array(
		'uses'=>'admincp\VideoController@get_duplicates_list_video',
		'middleware' => 'checkLogin'
		));

	Route::get('duplicates-video-detail/{id}',array(
		'uses'=>'admincp\VideoController@get_duplicates_video_detail',
		'middleware' => 'checkLogin'
		));

	Route::get('duplicates-list-video-detail/{id}',array(
		'uses'=>'admincp\VideoController@get_duplicates_list_video_detail',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-duplicates-video/{id}',array(
		'uses'=>'admincp\VideoController@get_delete_duplicates_video',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-all-duplicates-video',array(
		'uses'=>'admincp\VideoController@get_delete_all_duplicates_video',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-video-csv',array(
		'uses'=>'admincp\VideoController@get_delete_video_csv',
		'middleware' => 'checkLogin'
		));

	Route::post('auto-upload-csv',array(
		'uses'=>'admincp\VideoController@auto_upload_csv',
		'middleware' => 'checkLogin'
		));

	Route::post('delete-video-csv',array(
		'uses'=>'admincp\VideoController@post_delete_video_csv',
		'middleware' => 'checkLogin'
		));

	Route::get('list-video',array(
		'uses'=>'admincp\VideoController@postListVideo',
		'middleware' =>'checkLogin'
		));

	Route::get('video-cat-list-name/{cat_array}',array(
		'uses'=>'admincp\VideoController@get_categories_list',
		'middleware' =>'checkLogin'
		));

	Route::get('delete-all-check/{string_id}',array(
		'uses'=>'admincp\VideoController@deleteAllVideo',
		'middleware' =>'checkLogin'
		));

	Route::get('change-email',array(
		'uses'=>'admincp\LoginController@getChangeMail',
		'middleware' =>'checkLogin'
		));

	Route::post('change-email',array(
		'uses'=>'admincp\LoginController@postChangeMail',
		'middleware' =>'checkLogin'
		));

	Route::get('delete-payment/{id}',array(
			'uses' => 'admincp\LoginController@getDeletePayment',
			'middleware' => 'checkLogin'
		));

	Route::get('clear-cache', array(
	    'uses' => 'admincp\LoginController@clearAllCache',
	    'middleware' => 'checkLogin'
	));

	Route::get('tag',array(
		'uses'=>'admincp\LoginController@get_tag',
		'middleware' => 'checkLogin'
		));

	Route::post('tag-manage',array(
		'uses'=>'admincp\LoginController@post_list_tag',
		'middleware' => 'checkLogin'
		));

	Route::get('add-tag',array(
		'uses'=>'admincp\LoginController@get_add_tag',
		'middleware' => 'checkLogin'
		));

	Route::post('add-tag',array(
		'uses'=>'admincp\LoginController@post_add_tag',
		'middleware' => 'checkLogin'
		));

	Route::get('edit-tag/{id}',array(
		'uses'=>'admincp\LoginController@get_edit_tag',
		'middleware' => 'checkLogin'
		));

	Route::post('edit-tag/{id}',array(
		'uses'=>'admincp\LoginController@post_edit_tag',
		'middleware' => 'checkLogin'
		));

	Route::get('delete-tag/{id}',array(
			'uses' => 'admincp\LoginController@get_delete_tag',
			'middleware' => 'checkLogin'
		));

	Route::get('delete-all-tags-check/{string_id}',array(
		'uses'=>'admincp\LoginController@delete_all_tag',
		'middleware' =>'checkLogin'
		));
});
