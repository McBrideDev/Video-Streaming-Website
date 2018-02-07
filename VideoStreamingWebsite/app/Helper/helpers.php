<?php
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

function dumpComments($comments, $view = 'video.comment') {
  $commentList = '';
  //$user=\Session::get('User');
  // if (!\Session::has('User')) {
  //   return NULL;
  // }
  foreach ($comments as $comment) {
    $commentList .= view($view)->with('result', $comment)->render();
  }

  echo $commentList;
}

/**
 * truncate a string after n words
 * @param string $text
 * @param int $limit limit word
 * @return string
 */
function truncate($text, $limit) {
  $text = strip_tags($text);
  if (str_word_count($text, 0) > $limit) {
    $words = str_word_count($text, 2);
    $pos = array_keys($words);
    $text = substr($text, 0, $pos[$limit]) . '...';
  }
  return $text;
}

function getTimePayFortmat($date, $days) {
  // $date = strtotime("+".." days", strtotime($date));
  return date('Y-m-d', strtotime($date . ' +' . $days . ' days'));
}

function sec2hms($sec, $padHours = false) {
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

function convert_time($time = "00:00:00") {
  $time_array = explode(':', $time);
  // var_dump($time_array);die;
  $timer = 0;
  $hours = $minutes = $seconds = 0;
  if (count($time_array) == 3) {
    // die('dsd')/;
    $hours = intval($time_array[0]) * 3600;
    $minutes = intval($time_array[1]) * 60;
    $seconds = intval($time_array[2]);
    return $timer = $hours + $minutes + $seconds;
  } else {
    $minutes = intval($time_array[0]) * 60;
    $seconds = intval($time_array[1]);
    return $timer = $hours + $minutes + $seconds;
  }
}

function strimConvertTime($time = "") {
  $time_array = explode(' ', $time);
  $timer = 0;
  $hours = $minutes = $seconds = 0;
  if (count($time_array) === 5) {
    $getHours = explode('h', $time_array[0]);
    $timeHours = $getHours[0];
    $timeMin = $time_array[1];
    $timeSec = $time_array[3];
    $hours = intval($timeHours) * 3600;
    $minutes = intval($timeMin) * 60;
    $seconds = intval($timeSec);
    return $timer = $hours + $minutes + $seconds;
  }
  if (count($time_array) === 3) {
    $getHours = explode('h', $time_array[0]);
    $timeHours = $getHours[0];
    $timeMin = $time_array[1];
    $timeSec = 0;
    $hours = intval($timeHours) * 3600;
    $minutes = intval($timeMin) * 60;
    $seconds = intval($timeSec);
    return $timer = $hours + $minutes + $seconds;
  }
  if (count($time_array) === 4) {
    $timeHours = 0;
    $timeMin = $time_array[0];
    $timeSec = $time_array[3];
  }
  if (count($time_array) === 2) {
    $timeHours = 0;
    $timeMin = $time_array[0];
    $timeSec = 0;
    $hours = intval($timeHours) * 3600;
    $minutes = intval($timeMin) * 60;
    $seconds = intval($timeSec);
    return $timer = $hours + $minutes + $seconds;
  }
}

function get_title_datetime() {
  $date = new Datetime();
  $format = $date->format('l, F dS, Y');
  return trans('home.VIDEO_UPLOAD_ON') . " " . $format;
}

function getAvailableAppLangArray() {
  $locales[''] = Lang::get('app.select_your_language');

  foreach (Config::get('app.locales') as $key => $value) {
    $locales[$key] = $value;
  }
  return $locales;
}

function get_categories_list($cat_array) {
  $cat = explode(',', $cat_array);
  $cat_list = array();
  for ($i = 0; $i < count($cat); $i++) {
    $catID = explode("_", $cat[$i]);
    array_push($cat_list, $catID[0]);
  }
  $categories_name = App\Models\CategoriesModel::select('title_name')->whereIn('id', $cat_list)->where('status', '=', '1')->get();
  $currentCat = array();
  if (count($categories_name) > 0) {
    foreach ($categories_name as $value) {
      array_push($currentCat, $value->title_name);
    }
    return implode(",", $currentCat);
  }
  return;

  //echo implode(",",$categories_name->title_name);die;
}

function get_categories_list_link($cat_array) {
  $cat = explode(',', $cat_array);
  $cat_list = array();
  for ($i = 0; $i < count($cat); $i++) {
    $catID = explode("_", $cat[$i]);
    $cat_name = \App\Models\CategoriesModel::where('id', '=', $catID[0])->where('status', '=', '1')->first();
    if (!empty($cat_name)) {
      $html = '<a class="btn btn-default btn-xs" role="button" href="' . URL(getLang() . 'categories') . '/' . $cat_name->id . '.' . $cat_name->post_name . '.html">' . $cat_name->title_name . '</a>';
      array_push($cat_list, $html);
    }
  }

  return implode(',', $cat_list);
}

function cat_form_array($cat_array) {
  $cat_post = explode(',', $cat_array);
  $cat_list = array();
  for ($i = 0; $i < count($cat_post); $i++) {
    $catID = explode("_", $cat_post[$i]);
    array_push($cat_list, $catID[0]);
  }
  return implode(',', $cat_list);
}

function cat_array($cat_array) {
  $cat_post = explode(',', $cat_array);
  $cat_list = array();
  for ($i = 0; $i < count($cat_post); $i++) {
    $catID = explode("_", $cat_post[$i]);
    array_push($cat_list, $catID[0]);
  }
  return $cat_list;
}

function cat_array_name($cat_array) {
  $cat_post = explode(',', $cat_array);
  $cat_list = array();
  for ($i = 0; $i < count($cat_post); $i++) {
    $catID = explode("_", $cat_post[$i]);
    array_push($cat_list, $catID[1]);
  }
  return implode(',', $cat_list);
}

function get_cat_video_id($id, $video_list) {
  $temp = array();
  $tempvideoID = array();
  for ($i = 0; $i < count($video_list); $i++) {
    array_push($temp, $video_list[$i]->string_Id . "_" . $video_list[$i]->cat_id);
  }
  for ($i = 0; $i < count($temp); $i++) {
    $videoID = explode('_', $temp[$i]);
    $cat_id = $videoID[1];
    $cat_array = explode(',', $cat_id);
    if (in_array($id, $cat_array)) {
      array_push($tempvideoID, $videoID[0]);
    }
  }
  return $tempvideoID;
}

function get_video_form_cat($id) {
  return App\Helper\VideoHelper::getRandomByCategoryId($id);
}

function count_video_in_cat($id) {
  $count = \App\Models\VideoCatModel::where('video_cat.cat_id', '=', $id)->count();
  if ($count == 1 or $count == 0) {
    return $count . " Video";
  } else {
    return $count . " Videos";
  }
}

function count_video_in_channel($id) {
  $count = \App\Models\VideoModel::where('channel_Id', '=', $id)->count();
  if ($count == 1 or $count == 0) {
    return $count . " Video";
  } else {
    return $count . " Videos";
  }
}

function get_client_ip() {
  // check for shared internet/ISP IP
  if (!empty($_SERVER['HTTP_CLIENT_IP']) && validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
    return $_SERVER['HTTP_CLIENT_IP'];
  }

  // check for IPs passing through proxies
  if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    // check if multiple ips exist in var
    if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
      $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
      foreach ($iplist as $ip) {
        if (validate_ip($ip))
          return $ip;
      }
    } else {
      if (validate_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
  }
  if (!empty($_SERVER['HTTP_X_FORWARDED']) && validate_ip($_SERVER['HTTP_X_FORWARDED']))
    return $_SERVER['HTTP_X_FORWARDED'];
  if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
    return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
  if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
    return $_SERVER['HTTP_FORWARDED_FOR'];
  if (!empty($_SERVER['HTTP_FORWARDED']) && validate_ip($_SERVER['HTTP_FORWARDED']))
    return $_SERVER['HTTP_FORWARDED'];

  // return unreliable ip since all else failed
  return $_SERVER['REMOTE_ADDR'];
}

function pornhub_param_url_time_start() {
  $start = date('Y-m-d H:i:s');
  return strtotime($start);
}

function pornhub_param_url_time_end() {
  $end = strtotime('+2 hours', pornhub_param_url_time_start());
  return $end;
}

function get_categories() {
  return App\Helper\AppHelper::getCategoryList();
}

function StandardAdHome() {
  $data = \App\Models\StandardAdsModel::get_standard_home();
  return $data;
}

function StandardAdFooter() {
  $data = \App\Models\StandardAdsModel::get_standard_footer();
  return $data;
}

function StandardAdTopRate() {
  $data = \App\Models\StandardAdsModel::get_standard_toprate();
  return $data;
}

function StandardAdMostView() {
  $data = \App\Models\StandardAdsModel::get_standard_mostview();
  return $data;
}

function StandardAdVideo() {
  $data = \App\Models\StandardAdsModel::get_standard_video();
  return $data;
}

function StandardAdPornstar() {
  $data = \App\Models\StandardAdsModel::get_standard_pornstar();
  return $data;
}

function GetVideoConfig() {
  return \App\Helper\AppHelper::getVideoConfig();
}

function GetSiteConfig() {
  $setting = \App\Helper\AppHelper::getSiteConfig();
  return $setting;
}

function GetPlayerAds() {
  return \App\Helper\AppHelper::getPlayerAds();
}

function GetRatingVideo($videoID) {
  return App\Helper\VideoHelper::getRating($videoID);
}

function CheckWatchingVideo() {
  $checkwatching = \App\Models\WatchNowModel::check_watch();
  return $checkwatching;
}

function CheckBanIP() {
  $IPadress = \App\Models\BanIPModel::get_list_ban();
  return $IPadress;
}

function GetPaymentConfig() {
  return App\Helper\AppHelper::getPaymentConfig();
}

function GetStaticPage($id) {
  $staticpage = \App\Models\StaticPageModel::show_link($id);
  return $staticpage;
}

function GetHeaderNews() {
  return App\Helper\AppHelper::getHeaderLinks();
}

function is_404($url) {
  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);

  /* Get the HTML or whatever is linked in $url. */
  $response = curl_exec($handle);

  /* Check for 404 (file not found). */
  $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
  curl_close($handle);

  /* If the document has loaded successfully without any redirection or error */
  if ($httpCode >= 200 && $httpCode < 300) {
    return false;
  } else {
    return true;
  }
}

function CountMessageMember($form) {
  $c_message = App\Models\MemberMessageModel::where('frommember', '=', $form)->count();
  return $c_message;
}

function GetAllMessage($form) {
  $allmessage = \App\Models\MemberMessageModel::select('members.firstname', 'members.lastname', 'member_message.*')
          ->where('member_message.frommember', '=', $form)
          //->where('member_message.tomember','=',$frommember)
          ->join('members', 'members.user_id', '=', 'member_message.frommember')
          ->get();
  return $allmessage;
}

function GetMemberName($uID) {
  $member_name = \App\Models\MemberModel::where('user_ID', '=', $uID)->first();
  return $member_name;
}

function CheckUserPayment($userID, $videoID) {
  //$checkUse = \App\Models\SubsriptionModel::check_user_buy_video($userID, $videoID);
  //return $checkUse;
  return App\Helper\VideoHelper::isPurchased($userID, $videoID);
}

//get link server http://www.4tube.com/

function ResetURLVideo($url, $host) {

  $call_c = new App\Http\Controllers\admincp\DownloaderController();
  $data = $call_c->get_info_video($url, $host);
  return $data;
}

function getLang() {
  // return \App\Helper\AppHelper::getLang();

  // $callSetting = \App\Models\LanguageSettingsModel::first();
  // if (!empty($callSetting)) {
  //  if ($callSetting->isLanguage === 'active') {

  //    $checkUri = \App\Models\LanguageModel::select('languageCode')->whereIn('languageCode', [Request::segment(1)])->first();
  //    if (!empty($checkUri)) {
  //      return '/' . $checkUri->languageCode . '/';
  //    }
  //    //load Default
  //    $loadLanguage = \App\Models\LanguageModel::find($callSetting->defaultLanguage);
  //    if (!empty($loadLanguage)) {
  //      return '/' . $loadLanguage->languageCode . '/';
  //    } else {
  //      return NULL;
  //    }
  //  }
  //  return NULL;
  // }
  // return NULL;

  return '/' . LaravelLocalization::getCurrentLocale() . '/';
}

function getListLanguage() {
  return \App\Helper\LanguageHelper::getActiveLanguges();
}

function perPage() {
  $perPage = \App\Helper\AppHelper::getSiteConfig()->perPage;
  return $perPage;
}

function seo_friendly_url($string){
  $string = str_replace(array('[\', \']', '`'), '', $string);
  $string = preg_replace('/\[.*\]/U', '', $string);
  $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
  $string = htmlentities($string, ENT_COMPAT, 'utf-8');
  $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
  $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
  return strtolower(trim($string, '-'));
}

function nice_string($string){
  $string = str_replace(array('[\', \']', '`'), '', $string);
  $string = preg_replace('/\[.*\]/U', '', $string);
  $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
  $string = htmlentities($string, ENT_COMPAT, 'utf-8');
  $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
  $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
  return strtolower(trim($string, '-'));
}
