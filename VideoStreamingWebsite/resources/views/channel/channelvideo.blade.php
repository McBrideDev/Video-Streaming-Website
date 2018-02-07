<?php
use App\Helper\AppHelper;
use App\Helper\VideoHelper;
?>
@extends('master-frontend')
@section('title', $channel->title_name)
@section('content')
@section('script')
<link href="//vjs.zencdn.net/5.4.6/video-js.css" rel="stylesheet">
<link href="{{URL::asset('public/assets/css/videojs_ads.css')}}" rel="stylesheet">
<script src="{{URL::asset('public/assets/js/videojs_ads.js')}}"></script>
<script src="{{URL::asset('public/assets/js/player.js')}}"></script>
@endsection
<script type="text/javascript">
<?php
$v_setting = AppHelper::getVideoConfig();
if (!empty(GetPlayerAds())) {
	$get_ads_video = GetPlayerAds();
}
if (isset($addvideo)) {
	if ($addvideo->website == "www.pornhub.com" or $addvideo->website == "www.maxjizztube.com" or $addvideo->website == "www.xvideos.com" or $addvideo->website == "www.youporn.com" or $addvideo->website == "www.4tube.com" or $addvideo->website == "lubetube.com" or $addvideo->website == "xhamster.com") {
	$reset_data = ResetURLVideo($addvideo->video_url, $addvideo->website);

	$video_url = $reset_data['link'];
	?>

	var videoData = <?=
	json_encode([
		'embedCode' => $addvideo->isEmbed === 'yes' ? $addvideo->embedCode : NULL,
		'videoId' => $addvideo->string_Id,
		'videoServer' => $addvideo->website,
		'videoUrl' => $video_url,
		'videoFile' => $addvideo->uploadName !== NULL ? json_decode($addvideo->uploadName) : NULL,
		'videoSD' => NULL,
		'videoHD' => NULL,
		'mobileVideo' => $reset_data['mobileVideo'] !== NULL ? $reset_data['mobileVideo'] : NULL,
		'videoPoster' => $addvideo->poster,
		'reload' => $v_setting->video_reload,
		'isBuy' => $addvideo->buy_this == '1' ? true : false,
		'playerLogo' => asset('public/upload/player/' . $v_setting->player_logo),
		'playerLoading' => asset('public/upload/player/' . $v_setting->player_loading),
		'xvideoServer' => $addvideo->website == "www.xvideos.com" || $addvideo->website == "www.maxjizztube.com" ? true : false,
		'videoType' => @get_headers($video_url)[3] === 'Content-Type: video/x-flv' ? 'video/x-flv' : 'video/mp4',
		'isAdvertisement' => $v_setting->is_ads == '1' ? true : false,
		'skipAds' => isset($get_ads_video) ? (int) ($v_setting->time_skip_ads) : 0,
		'adsName' => isset($get_ads_video) ? $get_ads_video->string_id : '',
		'serverAdspath' => isset($get_ads_video) ? $get_ads_video->media : '',
		'adsPath' => isset($get_ads_video) ? $get_ads_video->media !== '' ? explode('/', $get_ads_video->media)[1] . '/' . explode('/', $get_ads_video->media)[2] : NULL : NULL,
		'adsLink' => isset($get_ads_video) ? $get_ads_video->adv_url : '',
		'isMember' => \Session::has('User') ? ['checkPay' => \App\Models\SubsriptionModel::select('subscriptionId')->where('user_id', '=', \Session::get('User')->user_id)->where('video_id', '=', $addvideo->string_Id)->first()] : NULL,
		'expriedPay' => \Session::has('User') ? ['expriedDate' => !empty(\App\Models\SubsriptionModel::where('user_id', '=', \Session::get('User')->user_id)->where('video_id', '=', $addvideo->string_Id)->first()) ? strtotime(getTimePayFortmat(\App\Models\SubsriptionModel::where('user_id', '=', \Session::get('User')->user_id)->where('video_id', '=', $addvideo->string_Id)->first()->timestamp, \App\Models\SubsriptionModel::where('user_id', '=', \Session::get('User')->user_id)->where('video_id', '=', $addvideo->string_Id)->first()->initialPeriod)) - time() : -1] : NULL
	]);
	?>

	<?php } else { ?>
	var videoData = <?=
	json_encode([
		'embedCode' => $addvideo->isEmbed === 'yes' ? $addvideo->embedCode : NULL,
		'videoId' => $addvideo->string_Id,
		'videoServer' => $addvideo->website,
		'videoUrl' => NULL,
		'videoFile' => $addvideo->uploadName !== NULL ? json_decode($addvideo->uploadName) : NULL,
		'videoSD' => $addvideo->video_sd != NULL ? $addvideo->video_sd : $addvideo->video_src,
		'videoHD' => $addvideo->video_src,
		'mobileVideo' => NULL,
		'videoPoster' => $addvideo->poster,
		'reload' => $v_setting->video_reload,
		'isBuy' => $addvideo->buy_this == '1' ? true : false,
		'playerLogo' => asset('public/upload/player/' . $v_setting->player_logo),
		'playerLoading' => asset('public/upload/player/' . $v_setting->player_loading),
		'xvideoServer' => $addvideo->website == "www.xvideos.com" || $addvideo->website == "www.maxjizztube.com" ? true : false,
		'isAdvertisement' => $v_setting->is_ads == '1' ? true : false,
		'videoType' => @get_headers($video_url)[3] == 'video/x-flv' ? 'video/x-flv' : 'video/mp4',
		'adsName' => isset($get_ads_video) ? $get_ads_video->string_id : '',
		'skipAds' => isset($get_ads_video) ? (int) ($v_setting->time_skip_ads) : 0,
		'adsPath' => isset($get_ads_video) ? $get_ads_video->media !== '' ? explode('/', $get_ads_video->media)[1] . '/' . explode('/', $get_ads_video->media)[2] : NULL : NULL,
		'adsLink' => isset($get_ads_video) ? $get_ads_video->adv_url : '',
		'serverAdspath' => isset($get_ads_video) ? $get_ads_video->media : '',
		'isMember' => \Session::has('User') ? ['checkPay' => \App\Models\SubsriptionModel::where('user_id', '=', \Session::get('User')->user_id)->where('video_id', '=', $addvideo->string_Id)->first()] : NULL,
		'expriedPay' => \Session::has('User') ? ['expriedDate' => !empty(\App\Models\SubsriptionModel::where('user_id', '=', \Session::get('User')->user_id)->where('video_id', '=', $addvideo->string_Id)->first()) ? strtotime(getTimePayFortmat(\App\Models\SubsriptionModel::where('user_id', '=', \Session::get('User')->user_id)->where('video_id', '=', $addvideo->string_Id)->first()->timestamp, \App\Models\SubsriptionModel::where('user_id', '=', \Session::get('User')->user_id)->where('video_id', '=', $addvideo->string_Id)->first()->initialPeriod)) - time() : -1] : NULL
	]);
	?>
	<?php }
} ?>
</script>
<div class="main-content page-channel">
	<div class="container-fluid">
		<h2>{{trans('home.YOU_ARE_WATCHING')}} : {{ $channel->title_name}}</h2>
		<div class="row">
			<div class="col-sm-4 col-xs-12 image-left pull-left">
				<div class="channel-left">
					<div  class="advertisement pull-left margin-l margin-t5">{{ $channel->title_name}}</div>
					<button type="button" id="on-subscriber" channel-data="{{ $channel->id}}" class="btn btn-default pull-right"><i class="fa fa-rss"></i> <?= $check_subscriber; ?></button>
				</div>
				<div class="clearfix "></div>
				<div class="channel-left">
					<p class="channel-hd-description"> {{ $channel->description}}</p>
					<img src="/public/upload/channel/{{$channel->poster}}"  class="img-responsive on-error-img" style="max-width: 100% !important; height: auto" alt="">
				</div>
				<div class="clearfix"></div>
				<div class="channel-left margin-t10">
					<div class="channel-total">
						<span class="pull-left">{{trans('home.VIDEOS')}}</span>
						<span class="pull-right">{{$totalvideo}}</span>
					</div>
					<div class="clearfix"></div>
					<div class="channel-total">
						<span class="pull-left">{{trans('home.VIEWS')}}</span>
						<span class="pull-right">{{$channel->total_view}}</span>
					</div>
					<div class="clearfix"></div>
					<div class="channel-total">
						<span class="pull-left">{{trans('home.SUBSCRIBERS')}}</span>
						<span class="pull-right"><?php echo count($subscriber); ?></span>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class=" margin-t10">
					<?php if (\Session::has('User')) {
					$user = \Session::get('User');
					$payment_config = GetPaymentConfig(); ?>
					<form action='https://bill.ccbill.com/jpost/signup.cgi' method="POST">
						<input type=hidden name=clientAccnum value='{{$payment_config->clientAccnum}}'>
						<input type=hidden name=clientSubacc value='{{$payment_config->clientSubacc}}'>
						<input type=hidden name=formName value='{{$payment_config->formName}}'>
						<input type=hidden name=language value='{{$payment_config->language}}' >
						<input type=hidden name=allowedTypes value='{{$payment_config->allowedTypes}}' >
						<input type=hidden name=subscriptionTypeId value='{{$payment_config->subscriptionTypeId}}' >
						<input type="hidden" name="formDigest" value="{{ csrf_token() }}">
						<input type="hidden" name="user_id" value="{{$user->user_id}}">
						<input type="hidden" name="channel" value="{{$channel->id}}">
						<input type="hidden" name="_token" value="{{ csrf_token()}}">
						<input type=submit name=submit class="btn btn-signup width-100 border-ra10 padingt-10 blods"  value='{{trans("home.JOIN")}} {{ $channel->title_name}}'>
					</form>
					<?php } ?>
					<!-- <button type="button" id="join-channel" data-name="{{ $channel->title_name}}" data-id="{{ $channel->ID}}" data-amount="10.00"  class="btn btn-signup width-100 border-ra10 padingt-10 blods">JOIN {{ $channel->title_name}}</button> -->
				</div>
			</div>
			<div class="col-sm-8 col-xs-12 image-rights" id="load-video">
				<video id="xstreamerPlayer" class="video-js vjs-default-skin box-boder" controls preload="none" data-setup='{ "techOrder":["html5", "flash"] }'  style="width:100%" height="480">
					<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
				</video>
			</div>
		</div>
		<!-- Video Of Channel -->
		<div class="clearfix" style="min-height: 5px;"></div>
		<h2>{{trans('home.VIDEOS_FROM')}} {{ $channel->title_name}}<span></span></h2>

		<div class="row content-image videos">
			@foreach($channelvideo as $result)
			<?php $rating = VideoHelper::getRating($result->string_Id); ?>
			<div class="col-xs-6 col-sm-3 col-md-2 image-left">
				<div class="col">
					<div class="col_img">
						<span class="hd">HD</span>
						<a href="{{URL(getLang())}}/channel.html&type={{$channel->id}}&video={{$result->string_Id}}">
							<?php if ($result->poster == NULL) { ?>
								<img src="{{URL('public/assets/images/no-image.jpg')}}" alt="{{$result->title_name}}" class="img-responsive" />
							<?php } else { ?>
								<img data-preview ="{{$result->preview}}" data-src="{{$result->poster}}" class="js-videoThumbFlip img-responsive on-error-img" data-digitsSuffix="{{$result->digitsSuffix}}" data-digitsPreffix="{{$result->digitsPreffix}}" data-from="{{$result->website}}" src="{{$result->poster}}" alt="{{$result->title_name}}" />
							<?php } ?>
							<div class="position_text">
								<p class="time_minimute">{{sec2hms($result->duration)}}</p>
							</div>
					</div>
					<h3><a href="{{URL(getLang())}}/channel.html&type={{$channel->id}}&video={{$result->string_Id}}">{{$result->title_name}}</a></h3>
					<span class="titleviews">{{$result->total_view ===NULL ? 0: $result->total_view}} {{trans('home.VIEWS')}}  <span class="titlerating"><i class="fa fa-thumbs-up" aria-hidden="true"></i> {{floor($rating['percent_like'])}}%</span></span>
				</div>
			</div>
			@endforeach
			<div class="clearfix"></div>
			<div class="page_navigation">
				{!!$channelvideo->render()!!}
			</div>
		</div>
		<div style="height: 30px;" class="clearfix"></div>
		<h2>{{trans('home.RELATED_CHANNELS')}}  </h2>
		<div class="channel-bg" style="padding-bottom: 0px;padding-left: 15px;padding-right: 15px; margin-bottom: 15px;">
			<h3></h3>
			<div class="row main-channel">
				@foreach($related as $result)
				<?php
				$countvideo = count_video_in_channel($result->id);
				?>
				<div class="col-xs-6 col-sm-6 col-md-3">
					<div class="channel-col">
						<a href="{{URL(getLang().'channel')}}/{{$result->id}}/{{$result->post_name}}"><img src="{{$result->poster}}" alt="{{$result->title_name}}" width="277" height="111" class="on-error-img" /></a>
						<h4><a href="{{URL(getLang().'channel')}}/{{$result->id}}/{{$result->post_name}}">{{$result->title_name}}</a><span> {{$countvideo}}</span></h4>
					</div>
				</div>
				@endforeach
			</div>
		</div>

	</div>
</div>

<div id="channel-subscriber-success" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="panel panel-primary">
			<!-- <div class="panel-heading">Subscribe</div> -->
			<div class="panel-body">
				<div id="channel-msg" style="color: #000;font-weight: 300;" class="msg-modal"></div>
				<center><input type="button" data-dismiss="modal" class="btn btn-signup" style="margin-right: 5px;" value="Close"></center>
			</div>
		</div>
	</div>
</div>
@endsection
