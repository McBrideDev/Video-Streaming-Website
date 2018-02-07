<?php
use App\Helper\AppHelper;
use App\Helper\VideoHelper;
?>
@extends('master-frontend')
@section('title', $channel->title_name)
@section('script')
<!-- <script src="{{URL::asset('public/assets/js/videojs-preroll.js')}}"></script> -->
<!-- <link href="http://vjs.zencdn.net/5.4.6/video-js.css" rel="stylesheet">
<script src="http://vjs.zencdn.net/5.4.6/video.js"></script>
<link href="{{URL::asset('public/assets/css/videojs.ads.css')}}" rel="stylesheet">
<link href="{{URL::asset('public/assets/css/videojs-preroll.css')}}" rel="stylesheet" type="text/css">
<link href="{{URL::asset('public/assets/js/plugins/videojs_ads/videojs.watermark.css')}}" rel="stylesheet" type="text/css">
<link href="{{URL::asset('public/assets/js/plugins/videojs_ads/videojs.vast.vpaid.min.css')}}" rel="stylesheet">
<script src="{{URL::asset('public/assets/js/videojs.ads.js')}}"></script>
<script src="{{URL::asset('public/assets/js/plugins/videojs_ads/videojs.watermark.js')}}"></script>
<script src="{{URL::asset('public/assets/js/plugins/videojs_ads/videojs_5.vast.vpaid.js')}}"></script>
<script src="{{URL::asset('public/assets/js/plugins/videojs_ads/es5-shim.js')}}"></script>
<script src="{{URL::asset('public/assets/js/plugins/videojs_ads/ie8fix.js')}}"></script> -->
<link href="//vjs.zencdn.net/5.4.6/video-js.css" rel="stylesheet">
<link href="{{URL::asset('public/assets/css/videojs_ads.css')}}" rel="stylesheet">
<script src="{{URL::asset('public/assets/js/videojs_ads.js')}}"></script>
<script src="{{URL::asset('public/assets/js/player.js')}}"></script>
<script type="text/javascript">
<?php
$v_setting = AppHelper::getVideoConfig();
if (!empty(GetPlayerAds())) {
  $get_ads_video = GetPlayerAds();
}
if (isset($popular)) {
  if ($popular->website == "www.pornhub.com" or $popular->website == "www.maxjizztube.com" or $popular->website == "www.xvideos.com" or $popular->website == "www.youporn.com" or $popular->website == "www.4tube.com" or $popular->website == "lubetube.com" or $popular->website == "xhamster.com") {
	$reset_data = ResetURLVideo($popular->video_url, $popular->website);

	$video_url = $reset_data['link'];
	?>

	var videoData = <?=
	json_encode([
		'embedCode' => $popular->isEmbed === 'yes' ? $popular->embedCode : NULL,
		'videoId' => $popular->string_Id,
		'videoServer' => $popular->website,
		'videoUrl' => $video_url,
		'videoSD' => NULL,
		'videoHD' => NULL,
		'videoFile' => $popular->uploadName !== NULL ? json_decode($popular->uploadName) : NULL,
		'mobileVideo' => $reset_data['mobileVideo'] !== NULL ? $reset_data['mobileVideo'] : NULL,
		'videoPoster' => $popular->poster,
		'reload' => $v_setting->video_reload,
		'isBuy' => $popular->buy_this == '1' ? true : false,
		'playerLogo' => asset('public/upload/player/' . $v_setting->player_logo),
		'playerLoading' => asset('public/upload/player/' . $v_setting->player_loading),
		'xvideoServer' => $popular->website == "www.xvideos.com" || $popular->website == "www.maxjizztube.com" ? true : false,
		'videoType' => @get_headers($video_url)[3] === 'Content-Type: video/x-flv' ? 'video/x-flv' : 'video/mp4',
		'isAdvertisement' => $v_setting->is_ads == '1' ? true : false,
		'skipAds' => isset($get_ads_video) ? (int) ($v_setting->time_skip_ads) : 0,
		'adsName' => isset($get_ads_video) ? $get_ads_video->string_id : '',
		'serverAdspath' => isset($get_ads_video) ? $get_ads_video->media : '',
		'adsPath' => isset($get_ads_video) ? $get_ads_video->media !== '' ? explode('/', $get_ads_video->media)[1] . '/' . explode('/', $get_ads_video->media)[2] : NULL : NULL,
		'adsLink' => isset($get_ads_video) ? $get_ads_video->adv_url : '',
		'isMember' => \Session::has('User') ? ['checkPay' => \App\Models\SubsriptionModel::select('subscriptionId')->where('user_id', '=', \Session::get('User')->user_id)->where('video_id', '=', $popular->string_Id)->first()] : NULL,
		'expriedPay' => \Session::has('User') ? ['expriedDate' => !empty(\App\Models\SubsriptionModel::where('user_id', '=', \Session::get('User')->user_id)->where('video_id', '=', $popular->string_Id)->first()) ? strtotime(getTimePayFortmat(\App\Models\SubsriptionModel::where('user_id', '=', \Session::get('User')->user_id)->where('video_id', '=', $popular->string_Id)->first()->timestamp, \App\Models\SubsriptionModel::where('user_id', '=', \Session::get('User')->user_id)->where('video_id', '=', $popular->string_Id)->first()->initialPeriod)) - time() : -1] : NULL
	]);
	?>

  <?php } else { ?>
	var videoData = <?=
	json_encode([
		'embedCode' => $popular->isEmbed === 'yes' ? $popular->embedCode : NULL,
		'videoId' => $popular->string_Id,
		'videoServer' => $popular->website,
		'videoUrl' => NULL,
		'videoFile' => $popular->uploadName !== NULL ? json_decode($popular->uploadName) : NULL,
		'videoSD' => $popular->video_sd != NULL ? $popular->video_sd : $popular->video_src,
		'videoHD' => $popular->video_src,
		'mobileVideo' => NULL,
		'videoPoster' => $popular->poster,
		'reload' => $v_setting->video_reload,
		'isBuy' => $popular->buy_this == '1' ? true : false,
		'playerLogo' => asset('public/upload/player/' . $v_setting->player_logo),
		'playerLoading' => asset('public/upload/player/' . $v_setting->player_loading),
		'xvideoServer' => $popular->website == "www.xvideos.com" || $popular->website == "www.maxjizztube.com" ? true : false,
		'isAdvertisement' => $v_setting->is_ads == '1' ? true : false,
		'videoType' => @get_headers($video_url)[3] == 'video/x-flv' ? 'video/x-flv' : 'video/mp4',
		'adsName' => isset($get_ads_video) ? $get_ads_video->string_id : '',
		'skipAds' => isset($get_ads_video) ? (int) ($v_setting->time_skip_ads) : 0,
		'adsPath' => isset($get_ads_video) ? $get_ads_video->media !== '' ? explode('/', $get_ads_video->media)[1] . '/' . explode('/', $get_ads_video->media)[2] : NULL : NULL,
		'adsLink' => isset($get_ads_video) ? $get_ads_video->adv_url : '',
		'serverAdspath' => isset($get_ads_video) ? $get_ads_video->media : '',
		'isMember' => \Session::has('User') ? ['checkPay' => \App\Models\SubsriptionModel::where('user_id', '=', \Session::get('User')->user_id)->where('video_id', '=', $popular->string_Id)->first()] : NULL,
		'expriedPay' => \Session::has('User') ? ['expriedDate' => !empty(\App\Models\SubsriptionModel::where('user_id', '=', \Session::get('User')->user_id)->where('video_id', '=', $popular->string_Id)->first()) ? strtotime(getTimePayFortmat(\App\Models\SubsriptionModel::where('user_id', '=', \Session::get('User')->user_id)->where('video_id', '=', $popular->string_Id)->first()->timestamp, \App\Models\SubsriptionModel::where('user_id', '=', \Session::get('User')->user_id)->where('video_id', '=', $popular->string_Id)->first()->initialPeriod)) - time() : -1] : NULL
	]);
	?>
  <?php }
}
?>
</script>
@endsection
@section('content')
<div class="main-content page-channel">
	<div class="container-fluid">
		<h2>{{trans('home.YOU_ARE_WATCHING')}} : {{ $channel->title_name}}</h2>
		<div class="row">
			<div class="col-sm-4 col-xs-12 pull-left">
				<div class="channel-left">
					<div  class="advertisement pull-left margin-l margin-t5">{{ $channel->title_name}}</div>
					<button type="button" id="on-subscriber" channel-data="{{ $channel->id}}" class="btn btn-default pull-right"><i class="fa fa-rss"></i> <span id="txt_subscriber"><?= $check_subscriber; ?></spam></button>
				</div>
				<div class="clearfix "></div>
				<div class="channel-left">
					<p class="channel-hd-description"> {{ $channel->description}}</p>
					<img src="/public/upload/channel/{{$channel->poster}}" class="img-responsive on-error-img" style="max-width: 100% !important" alt="">
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
					<?php
					if (\Session::has('User')) {
						if (!$checkBuyChannel) {
							$user = \Session::get('User');
							$payment_config = GetPaymentConfig();
							?>
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
						<?php
						}
					}
					?>
					<!-- <button type="button" id="join-channel" data-name="{{ $channel->title_name}}" data-id="{{ $channel->ID}}" data-amount="10.00"  class="btn btn-signup width-100 border-ra10 padingt-10 blods">JOIN {{ $channel->title_name}}</button> -->
				</div>
			</div>
			@if($popular!=NULL)
			<div class="col-sm-8 col-xs-12 image-rights" id="load-video">
				<video id="xstreamerPlayer" class="video-js vjs-default-skin box-boder" controls preload="none" data-setup='{ "techOrder":["html5", "flash"] }'  style="width:100%" height="480">
					<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
				</video>
			</div>
			@else
			<div class="col-sm-8 col-xs-12 image-rights" id="load-video">
				<h4><center>{{trans('home.VIDEO_NOT_FOUND_FORM_CHANNEL')}}</center></h4>
				<div class="col-sm-12 image-right">
					<div class="ads-here-right">
						<p class="advertisement">ADVERTISEMENT</p>
						<?php echo StandardAdHome(); ?>
					</div>
				</div>
			</div>
			@endif
		</div>
		<!-- Video Of Channel -->
		<div class="clearfix" style="min-height: 5px;"></div>
		@if(count($channelvideo)>0)
		<h2>{{trans('home.VIDEOS_FROM')}} {{ $channel->title_name}}<span></span></h2>
		<div class="row content-image videos">
			@foreach($channelvideo as $result)
			<?php $rating = VideoHelper::getRating($result->string_Id); ?>
			<div class="col-xs-6 col-sm-3 col-md-2 image-left">
				<div class="col">
					<div class="col_img">
						<span class="hd">HD</span>
						<a href="{{URL(getLang())}}/channel.html&type={{$channel->id}}&video={{$result->string_Id}}">
							@if ($result->poster == NULL)
							  <img src="{{URL('public/assets/images/no-image.jpg')}}" alt="{{$result->title_name}}" class="img-responsive" />
							@else
							  <img data-preview ="{{$result->preview}}" data-src="{{$result->poster}}" class="js-videoThumbFlip img-responsive on-error-img" data-digitsSuffix="{{$result->digitsSuffix}}" data-digitsPreffix="{{$result->digitsPreffix}}" data-from="{{$result->website}}" src="{{$result->poster}}" alt="{{$result->title_name}}" />
							@endif
							<div class="position_text">
								<p class="time_minimute">{{sec2hms($result->duration)}}</p>
							</div>
					</div>
					<h3>
						<a href="{{URL(getLang())}}/channel.html&type={{$channel->id}}&video={{$result->string_Id}}">{{$result->title_name}}</a>
					</h3>
					<span class="titleviews">{{$result->total_view ===NULL ? 0: $result->total_view}} {{trans('home.VIEWS')}}  <span class="titlerating"><i class="fa fa-thumbs-up" aria-hidden="true"></i> {{floor($rating['percent_like'])}}%</span></span>
				</div>
			</div>
			@endforeach
			<div class="clearfix"></div>
			<div class="page_navigation">
				{!!$channelvideo->render()!!}
			</div>
		</div>
		@endif
		<div style="height: 30px;" class="clearfix"></div>
		<h2>{{trans('home.RELATED_CHANNELS')}}  </h2>
		<div class="channel-bg" style="padding-bottom: 0px;padding-left: 15px;padding-right: 15px; margin-bottom: 15px;">
			<h3 style="margin-top: 15px"></h3>
			<div class="row main-channel">
				@foreach($related as $result)
				<?php
				$countvideo = count_video_in_channel($result->id);
				?>
				<div class="col-xs-6 col-sm-4 col-md-3">
					<div class="channel-col text-center">
						<a href="{{URL(getLang().'channel')}}/{{$result->id}}/{{$result->post_name}}">
							<img src="/public/upload/channel/{{$result->poster}}" alt="{{$result->title_name}}" class="img-responsive on-error-img" />
						</a>
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
				<div style="color: #000;font-weight: 300;" id="channel-msg" class="msg-modal"></div>
				<center><input type="button" data-dismiss="modal" class="btn btn-signup" style="margin-right: 5px;" value="Close"></center>
			</div>
		</div>
	</div>
</div>
<div id="channel-pay" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="panel panel-primary">
			<div class="panel-heading">Join With {{ $channel->title_name}}</div>
			<div class="panel-body" >
				<!-- start -->
				<div align="center" id="payment-form-container"><div class="brick-wrapper ">
						<div class="brick-header">
							<div class="brick-merchant-name">Join With</div>
							<div class="brick-product-name">Anal</div>
						</div>
						<div id="err-container"></div>
						<form id="brick-payment-form" action="https://bill.ccbill.com/jpost/signup.cgi" method="post" class="brick-form">
							<div class="brick-iw-cc">
								<input class="brick-card-number brick-input-l" type="text" name="clientAccnum" value='900000'>
							</div>
							<div class="brick-iw-cc">
								<input class="brick-card-number brick-input-l" type="text" name="clientSubacc" value='0001'>
							</div>
							<div class="brick-iw-cc">
								<input class="brick-card-number brick-input-l" type="text" name="formName" value='13cc'>
							</div>
							<div class="brick-iw-cc">
								<input class="brick-card-number brick-input-l" type="text" name="language" value='English'>
							</div>
							<div class="brick-iw-cc">
								<input class="brick-card-number brick-input-l" type="text" name="allowedTypes" value='0000003361:840,0000004657:840,0000060748:840,0000060750:840,0000060752:840' >
							</div>
							<div class="brick-iw-cc">
								<input class="brick-card-number brick-input-l" type="text" name="subscriptionTypeId" value='0000004657:840'>
							</div>
							<div class="brick-iw-cc">
								<input class="brick-card-number brick-input-l" type="text" name="customVarName1" value="test">
							</div>
							<div class="brick-iw-cc">
								<input class="brick-card-number brick-input-l" type="text" name="customVarName2" value="test">
							</div>
							<button type="submit" class="brick-submit">
								<span class="brick-submit-text">Pay 9.99 USD</span>
								<div class="brick-loader-s"><div class="brick-loader-dots"></div></div>
							</button>
						</form>
					</div>

					<!-- end -->
					<div align="center"><input align="center" type="button" data-dismiss="modal" class="btn btn-signup margin-t5" style="margin-right: 5px;" value="CLose"> </div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
  $(document).ready(function () {
	  $(document).on('click', '#join-channel', function (e) {
		  e.preventDefault();
		  $('#channel-pay').modal('show');
	  })
  });
</script>
@endsection
