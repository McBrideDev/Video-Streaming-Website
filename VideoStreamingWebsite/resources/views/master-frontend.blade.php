<?php
use App\Helper\AppHelper;
use App\Helper\NetworkHelper;

$config = AppHelper::getSiteConfig();
$tags = AppHelper::getSiteTags();

$v_setting = AppHelper::getVideoConfig();
//$check_watch = CheckWatchingVideo();

//config for ads
$videoAds = null;
if (isset($viewvideo)) {
	$videoAds = AppHelper::getPlayerAds();
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<title>@yield('title') | {{$config->site_name}}</title>
		<?php if(isset($config->site_google_verification_code)){ ?>
		<meta name="google-site-verification" content="{{$config->site_google_verification_code}}" />
		<?php } ?>
		<meta name="description" content="<?php if(isset($viewvideo)){
			echo str_limit($viewvideo->description,170);
		} else { echo $config->site_description;} ?>"/>
		<meta name="keywords" content="{{$config->site_keyword}}"/>
		<meta property="og:url" content="<?= isset($viewvideo)? "".URL('watch')."/".$viewvideo->string_Id."/".$viewvideo->post_name.".html": URL() ?>" />
		<meta property="og:type" content="{{$config->site_name}}" />
		<meta property="og:title" content="@yield('title')" />
		<meta property="og:description" content="<?php if(isset($viewvideo)){
			echo str_limit($viewvideo->description,170);
		}else{ echo $config->site_description;} ?>" />
		<meta property="og:image" content="<?php if(isset($viewvideo)){
			echo $viewvideo->poster;
		}else{ echo URL('public/assets/images/logo.jpg');} ?>" />

		<script type="text/javascript">
			var base_url = window.location.origin+'{{getLang()!==NULL ? getLang(): "/"}}';
			var base_asset = '{{asset('')}}';
			var token = '{{csrf_token()}}';
			var video_width = "790";
			var video_height = "540";
			<?php
			if(isset($viewvideo)) {
				if($viewvideo->website=="www.pornhub.com"
						or $viewvideo->website=="www.maxjizztube.com"
						or $viewvideo->website=="www.xvideos.com"
						or $viewvideo->website=="www.4tube.com"
						or $viewvideo->website=="lubetube.com"
						or $viewvideo->website=="xhamster.com") {
					$reset_data = ResetURLVideo($viewvideo->video_url,$viewvideo->website);

					$video_url=$reset_data['link']; ?>;
					var videoData = <?=json_encode([
						'embedCode'       => $viewvideo->isEmbed === 'yes' ? $viewvideo->embedCode: NULL,
						'videoId'         => $viewvideo->string_Id,
						'videoServer'     => $viewvideo->website,
						'videoUrl'        => $viewvideo->video_url,
						'videoSD'         => NULL,
						'videoHD'         => NULL,
						'videoFile'       => $viewvideo->uploadName !==NULL ? json_decode($viewvideo->uploadName): NULL,
						'mobileVideo'     => $reset_data['mobileVideo'] !== NULL ? $reset_data['mobileVideo'] : NULL,
						'videoPoster'     => $viewvideo->poster,
						'reload'          => $v_setting->video_reload,
						'isBuy'           => $viewvideo->buy_this == '1' ? true: false,
						'playerLogo'      => asset('public/upload/player/'.$v_setting->player_logo),
						'playerLoading'   =>asset('public/upload/player/'.$v_setting->player_loading),
						'xvideoServer'    => $viewvideo->website=="www.xvideos.com" || $viewvideo->website=="www.maxjizztube.com"  ? true:false,
						'videoType'       => NetworkHelper::getVideoTypeFromUrl($video_url),
						'isAdvertisement' => $v_setting->is_ads == '1' ? true: false,
						'skipAds'         => $videoAds ? (int)($v_setting->time_skip_ads): 0,
						'adsName'         => $videoAds ? $videoAds->string_id:'',
						'serverAdspath'   => $videoAds ? $videoAds->media:'',
						'adsPath'         => $videoAds ? $videoAds->media!== '' ? explode('/', $videoAds->media)[1].'/'.explode('/', $videoAds->media)[2] : NULL: NULL,
						'adsLink'         => $videoAds ? $videoAds->adv_url:'',
						'isMember'        => \Session::has('User') ? [
										'checkPay' => \App\Models\SubsriptionModel::select('subscriptionId')
														->where('user_id','=',\Session::get('User')->user_id)
														->where('video_id','=',$viewvideo->string_Id)->first()
										] : NULL,
						'expriedPay' => \Session::has('User') ? [
										'expriedDate' => !empty(
										\App\Models\SubsriptionModel::where('user_id','=', \Session::get('User')->user_id)
											->where('video_id','=',$viewvideo->string_Id)->first()) ?
												strtotime(
													getTimePayFortmat(
														\App\Models\SubsriptionModel::where('user_id','=', \Session::get('User')->user_id)
															->where('video_id','=',$viewvideo->string_Id)->first()->timestamp,
														\App\Models\SubsriptionModel::where('user_id','=',\Session::get('User')->user_id)
															->where('video_id','=',$viewvideo->string_Id)->first()->initialPeriod
													)
												) - time() : -1 ] : NULL
						]);?>
		<?php } else { ?>
			var videoData = <?=json_encode([
				'embedCode'       => $viewvideo->isEmbed === 'yes' ? $viewvideo->embedCode: NULL,
				'videoId'         => $viewvideo->string_Id,
				'videoServer'     => $viewvideo->website,
				'videoUrl'        => $viewvideo->video_url !== NULL ? $viewvideo->video_url : NULL,
				'videoFile'       => $viewvideo->uploadName !== NULL ? json_decode($viewvideo->uploadName): NULL,
				'videoSD'         => $viewvideo->video_sd !== NULL ? $viewvideo->video_sd : $viewvideo->video_src,
				'videoHD'         => $viewvideo->video_src,
				'mobileVideo'     => NULL,
				'videoPoster'     => $viewvideo->poster,
				'reload'          => $v_setting->video_reload,
				'isBuy'           => $viewvideo->buy_this == '1' ? true : false,
				'playerLogo'      => asset('public/upload/player/'.$v_setting->player_logo),
				'playerLoading'   => asset('public/upload/player/'.$v_setting->player_loading),
				'xvideoServer'    => ($viewvideo->website=="www.xvideos.com" || $viewvideo->website=="www.maxjizztube.com")  ? true : false,
				'isAdvertisement' => $v_setting->is_ads == '1' ? true: false,
				'videoType'       => NetworkHelper::getVideoTypeFromUrl($viewvideo->video_sd),
				'adsName'         => $videoAds ? $videoAds->string_id:'',
				'skipAds'         => $videoAds ? (int)($v_setting->time_skip_ads): 0,
				'adsPath'         => $videoAds ? $videoAds->media!== '' ? explode('/', $videoAds->media)[1].'/'.explode('/', $videoAds->media)[2] : NULL: NULL  ,
				'adsLink'         => $videoAds ? $videoAds->adv_url:'',
				'serverAdspath'   => $videoAds ? $videoAds->media:'',
				'isMember'        => \Session::has('User') ? [
								'checkPay' => \App\Models\SubsriptionModel::where('user_id','=',\Session::get('User')->user_id)
												->where('video_id','=',$viewvideo->string_Id)->first()
								] : NULL,
				'expriedPay' => \Session::has('User') ? [
								'expriedDate' => !empty(\App\Models\SubsriptionModel::where('user_id', '=', \Session::get('User')->user_id)
														->where('video_id','=',$viewvideo->string_Id)->first()) ?
														strtotime(
															getTimePayFortmat(
																\App\Models\SubsriptionModel::where('user_id', '=', \Session::get('User')->user_id)
																		->where('video_id','=',$viewvideo->string_Id)->first()->timestamp,
																\App\Models\SubsriptionModel::where('user_id','=',\Session::get('User')->user_id)
																		->where('video_id','=',$viewvideo->string_Id)->first()->initialPeriod
															)
														) - time() : -1 ] : NULL
			]);?>
		<?php }
		} ?>;
		var language = <?=json_encode([
			'DELETED_SUCCESSFULLY'            => trans('home.DELETED_SUCCESSFULLY'),
			'VIDEO_NOT_FOUND'                => trans('home.VIDEO_NOT_FOUND'),
			'PLEASE_LOGIN_OR_SIGNUP_FOR_USE' => trans('home.PLEASE_LOGIN_OR_SIGNUP_FOR_USE'),
			'DELETED_UNSUCCESSFULLY'          => trans('home.DELETED_UNSUCCESSFULLY') ]);
		?>
		</script>
		<script src="{{URL('public/assets/js/all.js')}}" type="text/javascript" charset="utf-8"></script>
		{{-- <script src="{{URL('public/assets/js/videojs_ads.js')}}" type="text/javascript" charset="utf-8"></script> --}}
		<link href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
		<script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js" type="text/javascript"></script>
		<script async defer src="{{URL('public/assets/js/modernizr.dev.js')}}" type="text/javascript" charset="utf-8"></script>
		<script async defer src='https://www.google.com/recaptcha/api.js' type="text/javascript" charset="utf-8"></script>
		@yield('script')
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		{{-- TODO: temporary fix load sync, check where and why load async later --}}
		<link href="{{URL::asset('public/assets/css/all.css')}}" type="text/css" rel="stylesheet">
		<?php if($config->site_theme=="dark"): ?>
			<link href="{{URL::asset('public/assets/font-end/styles.css')}}" type="text/css" rel="stylesheet">
		<?php else: ?>
			<link href="{{URL::asset('public/assets/font-end/styles-v3.css')}}" type="text/css" rel="stylesheet">
		<?php endif;?>
		{{-- !!! TODO: temporary fix load sync, check where and why load async later --}}

		<noscript id="deferred-styles">
			<link href="{{URL::asset('public/assets/css/all.css')}}" type="text/css" rel="stylesheet">
			<?php if($config->site_theme=="dark"): ?>
			<link href="{{URL::asset('public/assets/font-end/styles.css')}}" type="text/css" rel="stylesheet">
			<?php else: ?>
			<link href="{{URL::asset('public/assets/font-end/styles-v3.css')}}" type="text/css" rel="stylesheet">
			<?php endif;?>
		</noscript>
		<script type="text/javascript">
			var loadDeferredStyles = function() {
				var addStylesNode = document.getElementById("deferred-styles");
				var replacement = document.createElement("div");
				replacement.innerHTML = addStylesNode.textContent;
				document.body.appendChild(replacement);
				addStylesNode.parentElement.removeChild(addStylesNode);
			};
			var raf = requestAnimationFrame || mozRequestAnimationFrame ||
					webkitRequestAnimationFrame || msRequestAnimationFrame;
			if (raf) {
				raf(function() { window.setTimeout(loadDeferredStyles, 0); });
			} else { window.addEventListener('load', loadDeferredStyles); }
		</script>
	</head>
	<body>
		@include('header.header')
		@yield('content')
		@include('footer.footer')
		@include('modal.siteModal')
		@if($config->site_ga!=NULL)<?=$config->site_ga;?>@endif
	</body>
</html>
