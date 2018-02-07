<?php
use App\Helper\AppHelper;

$config = AppHelper::getSiteConfig();
$v_setting = AppHelper::getVideoConfig();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$videovideo_embed->title_name?></title>
    <link rel="stylesheet" href="">
    <meta name="description" content="<?php if(isset($videovideo_embed)){ echo str_limit($videovideo_embed->description,170);}else{ echo $config->site_description;} ?>"/>
    <meta name="keywords" content="{{$config->site_keyword}}"/>
    <meta property="og:url"           content="<?= isset($videovideo_embed)? "".URL('watch')."/".$videovideo_embed->string_Id."/".$videovideo_embed->post_name.".html": URL() ?>" />
    <meta property="og:type"          content="{{$config->site_name}}" />
    <meta property="og:title"         content="<?=$videovideo_embed->title_name?>" />
    <meta property="og:description"   content="<?php if(isset($videovideo_embed)){ echo str_limit($videovideo_embed->description,170);}else{ echo $config->site_description;} ?>" />
    <meta property="og:image"         content="<?php if(isset($videovideo_embed)){ echo $videovideo_embed->poster;}else{ echo URL('public/assets/images/logo.jpg');} ?>" />
    <link rel="stylesheet" href="{{URL::asset('public/assets/font-awesome-4.3.0/css/font-awesome.min.css')}}">
    <link href="{{URL::asset('public/assets/font-end/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('public/assets/font-end/styles.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{URL::asset('public/assets/css/animate.min.css')}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="{{URL::asset('public/assets/js/bootstrap.min.js')}}"></script>
    <script src="{{URL('public/assets/js/jquery.bootstrap.newsbox.js')}}" type="text/javascript" charset="utf-8"></script>
    <link href="http://vjs.zencdn.net/5.4.6/video-js.css" rel="stylesheet">
    <script src="http://vjs.zencdn.net/5.4.6/video.js"></script>
    <link href="{{URL::asset('public/assets/css/videojs.ads.css')}}" rel="stylesheet">
    <link href="{{URL::asset('public/assets/css/videojs-preroll.css')}}" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('public/assets/js/plugins/videojs_ads/videojs.watermark.css')}}" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('public/assets/js/plugins/videojs_ads/videojs.vast.vpaid.min.css')}}" rel="stylesheet">
    <script src="{{URL::asset('public/assets/js/videojs.ads.js')}}"></script>
    <script src="{{URL::asset('public/assets/js/plugins/videojs_ads/videojs.watermark.js')}}"></script>
    <!-- <script src="{{URL::asset('public/assets/js/videojs-preroll.js')}}"></script> -->
    <script src="{{URL::asset('public/assets/js/plugins/videojs_ads/videojs_5.vast.vpaid.js')}}"></script>
    <script src="{{URL::asset('public/assets/js/plugins/videojs_ads/es5-shim.js')}}"></script>
    <script src="{{URL::asset('public/assets/js/plugins/videojs_ads/ie8fix.js')}}"></script>
    <script type="text/javascript">
    //var base_url = window.location.origin+'/adult'+'{{getLang()!==NULL ? getLang(): "/"}}';
    //var base_asset =  window.location.origin+"/adult";
    var base_url = window.location.origin+'{{getLang()!==NULL ? getLang(): "/"}}';
    var base_asset = '{{url('')}}/';
    var token = '{{csrf_token()}}'
    var video_width = "790";
    var video_height = "540";
    <?php
    if(!empty(GetPlayerAds())){
      $get_ads_video = GetPlayerAds();
    }

      if(isset($videovideo_embed)){
        if($videovideo_embed->website=="www.pornhub.com" or $videovideo_embed->website=="www.maxjizztube.com" or $videovideo_embed->website=="www.xvideos.com" or $videovideo_embed->website=="www.youporn.com" or $videovideo_embed->website=="www.4tube.com" or $videovideo_embed->website=="lubetube.com" or $videovideo_embed->website=="xhamster.com"){
            $reset_data=ResetURLVideo($videovideo_embed->video_url,$videovideo_embed->website);
            //dd($reset_data);
            $video_url=$reset_data['link']; ?>

            var videoData = <?=json_encode([
                'embedCode' => $videovideo_embed->isEmbed === 'yes' ? $videovideo_embed->embedCode: NULL,
                'videoId' => $videovideo_embed->string_Id,
                'videoServer' => $videovideo_embed->website,
                'videoUrl' => $video_url,
                'videoSD' => NULL,
                'videoHD' => NULL,
                'videoFile' =>$videovideo_embed->uploadName !==NULL ? json_decode($videovideo_embed->uploadName): NULL,
                'mobileVideo' => $reset_data['mobileVideo'] !==NULL ? $reset_data['mobileVideo'] : NULL,
                'videoPoster' => $videovideo_embed->poster,
                'reload' => $v_setting->video_reload,
                'isBuy' => $videovideo_embed->buy_this == '1' ? true: false,
                'playerLogo' => asset('public/upload/player/'.$v_setting->player_logo),
                'playerLoading' =>asset('public/upload/player/'.$v_setting->player_loading),
                'xvideoServer' => $videovideo_embed->website=="www.xvideos.com" || $videovideo_embed->website=="www.maxjizztube.com"  ? true:false,
                'videoType' => @get_headers($video_url)[3] === 'Content-Type: video/x-flv' ? 'video/x-flv' : 'video/mp4',
                'isAdvertisement' => $v_setting->is_ads == '1' ? true: false,
                'skipAds' => isset($get_ads_video)? (int)($v_setting->time_skip_ads): 0,
                'adsName' => isset($get_ads_video)? $get_ads_video->string_id:'',
                'serverAdspath' => isset($get_ads_video)? $get_ads_video->media:'',
                'adsPath' => isset($get_ads_video)? $get_ads_video->media!== '' ? explode('/', $get_ads_video->media)[1].'/'.explode('/', $get_ads_video->media)[2] : NULL: NULL  ,
                'adsLink' => isset($get_ads_video)? $get_ads_video->adv_url:'',
                'isMember' => \Session::has('User') ? ['checkPay' => \App\Models\SubsriptionModel::select('subscriptionId')->where('user_id','=',\Session::get('User')->user_id)->where('video_id','=',$videovideo_embed->string_Id)->first()]: NULL,
                'expriedPay' => \Session::has('User') ? ['expriedDate' => !empty(\App\Models\SubsriptionModel::where('user_id','=',\Session::get('User')->user_id)->where('video_id','=',$videovideo_embed->string_Id)->first())?  strtotime(getTimePayFortmat(\App\Models\SubsriptionModel::where('user_id','=',\Session::get('User')->user_id)->where('video_id','=',$videovideo_embed->string_Id)->first()->timestamp, \App\Models\SubsriptionModel::where('user_id','=',\Session::get('User')->user_id)->where('video_id','=',$videovideo_embed->string_Id)->first()->initialPeriod)) - time() : -1 ] : NULL
                ]);?>

            <?php } else{ ?>
             var videoData = <?=json_encode([
                'embedCode' => $videovideo_embed->isEmbed === 'yes' ? $videovideo_embed->embedCode: NULL,
                'videoId' => $videovideo_embed->string_Id,
                'videoServer' => $videovideo_embed->website,
                'videoUrl' => NULL,
                'videoFile' =>$videovideo_embed->uploadName !==NULL ? json_decode($videovideo_embed->uploadName): NULL,
                'videoSD' => $videovideo_embed->video_sd !=NULL? $videovideo_embed->video_sd : $videovideo_embed->video_src,
                'videoHD' => $videovideo_embed->video_src,
                'mobileVideo' => NULL,
                'videoPoster' => $videovideo_embed->poster,
                'reload' => $v_setting->video_reload,
                'isBuy' => $videovideo_embed->buy_this == '1' ? true: false,
                'playerLogo' => asset('public/upload/player/'.$v_setting->player_logo),
                'playerLoading' =>asset('public/upload/player/'.$v_setting->player_loading),
                'xvideoServer' => $videovideo_embed->website=="www.xvideos.com" || $videovideo_embed->website=="www.maxjizztube.com"  ? true:false,
                'isAdvertisement' => $v_setting->is_ads == '1' ? true: false,
                'videoType' => @get_headers($video_url)[3] == 'video/x-flv' ? 'video/x-flv' : 'video/mp4',
                'adsName' => isset($get_ads_video)? $get_ads_video->string_id:'',
                'skipAds' => isset($get_ads_video)? (int)($v_setting->time_skip_ads): 0,
                'adsPath' => isset($get_ads_video)? $get_ads_video->media!== '' ? explode('/', $get_ads_video->media)[1].'/'.explode('/', $get_ads_video->media)[2] : NULL: NULL  ,
                'adsLink' => isset($get_ads_video)? $get_ads_video->adv_url:'',
                'serverAdspath' => isset($get_ads_video)? $get_ads_video->media:'',
                'isMember' => \Session::has('User') ? ['checkPay' => \App\Models\SubsriptionModel::where('user_id','=',\Session::get('User')->user_id)->where('video_id','=',$videovideo_embed->string_Id)->first()]: NULL,
                'expriedPay' => \Session::has('User') ? ['expriedDate' => !empty(\App\Models\SubsriptionModel::where('user_id','=',\Session::get('User')->user_id)->where('video_id','=',$videovideo_embed->string_Id)->first())?  strtotime(getTimePayFortmat(\App\Models\SubsriptionModel::where('user_id','=',\Session::get('User')->user_id)->where('video_id','=',$videovideo_embed->string_Id)->first()->timestamp, \App\Models\SubsriptionModel::where('user_id','=',\Session::get('User')->user_id)->where('video_id','=',$videovideo_embed->string_Id)->first()->initialPeriod)) - time() : -1 ] : NULL
                ]);?>
            <?php }} ?>

            var language = <?=json_encode([
                'DELETED_SUCCESSFULLY'   => trans('home.DELETED_SUCCESSFULLY'),
                'VIDEO_NOT_FOUND' => trans('home.VIDEO_NOT_FOUND'),
                'PLEASE_LOGIN_OR_SIGNUP_FOR_USE' => trans('home.PLEASE_LOGIN_OR_SIGNUP_FOR_USE'),
                'DELETED_UNSUCCESSFULLY' => trans('home.DELETED_UNSUCCESSFULLY')
                ]);?>
        </script>
</head>
<body>
<div id="video-player" class="box-boder" style="width: 100%; height: 100%;">
    <video id="xstreamerPlayer" class="video-js vjs-default-skin" controls preload="none" data-setup='{ "techOrder":["html5", "flash"] }'  style="width: 100%; height: 100%;">
        <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
    </video>
</div>

<script src="{{URL::asset('public/assets/js/player.js?v='.time())}}"></script>
</body>
</html>
