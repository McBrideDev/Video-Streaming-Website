<?php

use App\Helper\AppHelper;
use App\Helper\VideoHelper;

$v_setting = AppHelper::getVideoConfig();
?>
@extends('master-frontend')
@section('title', $viewvideo->title_name)
@section('script')

<link href="//vjs.zencdn.net/5.4.6/video-js.css" rel="stylesheet">
<link href="{{URL::asset('public/assets/css/videojs_ads.css')}}" rel="stylesheet">
<script src="{{URL::asset('public/assets/js/videojs_ads.js')}}"></script>
<script src="{{URL::asset('public/assets/js/videojs-preroll.js')}}"></script>

@endsection
@section('content')
<div class="main-content">
	<div class="container-fluid">
		<h2>{{trans('home.YOU_ARE_WATCHING')}} : {{ $viewvideo->title_name}}</h2>
		<div class="row">
			<div class="col-sm-9 box-video"><!-- box video -->
				<div class="clearfix"></div>
				<div id="video-player" class="box-boder">
					<video id="xstreamerPlayer" class="video-js vjs-default-skin" controls preload="none" data-setup='{ "techOrder":["flash", "html5"] }' style="width:100%; min-height: 550px" height="550">
						<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
					</video>
				</div>
				<div class="clearfix"></div>
				<div class="box-boder ads-under-player hidden-xs hidden-sm ">
					{!!\App\Models\StandardAdsModel::get_standard_belove_video()!!}
				</div>
				<div class="clearfix"></div>
				<div class="box-referent"><!-- Box referent -->
					<div class="container-fluid"><!-- container -->
						<div>
							<div class="vote-box col-xs-7 col-sm-5 col-md-3 col-lg-3">
								<div id="vote_msg" class="vote-msg">
									<div class="btn-group btn-group-lg btn-group-justified" role="group">
										<div class="btn-group btn-group-lg" role="group">
											<a href="#" role="button" class="btn btn-default" id="vote_like_{{$viewvideo->string_Id}}">
												<i class="glyphicon glyphicon-thumbs-up"></i> <span class="hidden-xs">{{trans('home.LIKE')}}</span>
											</a>
										</div>
										<div class="btn-group btn-group-lg" role="group">
											<div class="text-white text-center">
												<span id="video_rate_number">{{$percent_like['percent_like']}}%</span>(<span id="video_likes">{{$percent_like['like']}}</span>/<span id="video_dislikes">{{$percent_like['dislike']}}</span>)
											</div>
											<div class="dislikes <?= ($percent_like['dislike'] != 0) ? '' : 'not-voted' ?> " style="display: block;margin: 0 5px;position: relative;">
												<div id="video_rate" class="likes" style="width:{{$percent_like['percent_like']}}%;"></div>
											</div>
										</div>
										<div class="btn-group btn-group-lg" role="group">
											<a href="#" role="button" class="btn btn-default" id="vote_dislike_{{$viewvideo->string_Id}}">
												<i class="glyphicon glyphicon-thumbs-down"></i>
											</a>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="pull-right m-t-15">
								@if(\Session::has('User'))
									@if ($v_setting->is_embed == 1)
										<div id="embed_video" class="pull-right m-r-5">
											<a href="#embed_video" class="btn btn-default">
												<i class="glyphicon glyphicon-link"></i>
												<span class="hidden-xs">{{trans('home.EMBED')}}</span>
											</a>
										</div>
									@endif
									@if ($v_setting->is_favorite == 1)
										<div id="favorite" class="pull-right m-r-5">
											<a href="javascript:void(0);" class="btn btn-default">
												<i class="glyphicon glyphicon-heart<?= $check_favorite == 'true' ? ' pink' : '' ?>"></i>
												(<span id="change_favorited" class="hidden-xs"><?= $count_favorite; ?></span>)
											</a>
										</div>
									@endif
									@if ($v_setting->is_download == 1 && $viewvideo->website == 'upload')
										<div id="download" class="pull-right m-r-5">
											<a href="javascript:void(0);" class="btn btn-default">
												<i class="glyphicon glyphicon-download-alt"></i>
												<span class="hidden-xs">{{trans('home.DOWNLOAD')}}</span>
											</a>
										</div>
									@endif
								@else
									@if ($v_setting->is_embed == 1)
										<div  class="pull-right m-r-5">
											<a href="javascript:void(0);" data-toggle="modal" data-target="#subscribe" class="btn btn-default">
												<i class="glyphicon glyphicon-link"></i>
												<span class="hidden-xs">{{trans('home.EMBED')}}</span>
											</a>
										</div>
									@endif
									@if ($v_setting->is_favorite == 1)
										<div  class="pull-right m-r-5">
											<a href="javascript:void(0);" data-toggle="modal" data-target="#subscribe" class="btn btn-default">
												<i class="glyphicon glyphicon-heart"></i>
												(<span class="hidden-xs"><?= $count_favorite; ?></span>)
											</a>
										</div>
									@endif
									{{-- @if ($v_setting->is_download == 1)
										<div  class="pull-right m-r-5">
											<a href="javascript:void(0);" data-toggle="modal" data-target="#subscribe" class="btn btn-default">
												<i class="glyphicon glyphicon-download-alt"></i>
												<span class="hidden-xs">{{trans('home.DOWNLOAD')}}</span>
											</a>
										</div>
									@endif --}}
									<div class="clearfix"></div>
								@endif
							</div>
						</div>
						<div>
							<div id="response_message" style="display: none;"></div>
						</div>
						<div>
							<div id="embed_video_box" class="m-t-15" style="display: none;">
								<a href="#close_embed" id="close_embed" class="close">×</a>
								<div class="separator">{{trans('home.EMBED')}} {{trans('home.VIDEOS')}}</div>
								<div class="form-horizontal">
									<div class="form-group">
										<label for="video_embed_code" class="col-lg-3 control-label">{{trans('home.EMBED')}}</label>
										<div class="col-lg-9">
											<textarea name="video_embed_code" rows="6" id="video_embed_code" class="form-control">
											 	<iframe src="{{URL(getLang())}}/embedframe/{{$viewvideo->string_Id}}" allowfullscreen frameborder="0" width="510" height="400" scrolling="no" sandbox="allow-same-origin allow-scripts allow-popups allow-froms"></iframe>
											</textarea>
										</div>
									</div>
									<div id="custom_size" class="form-group">
										<label for="custom_width" class="col-lg-3 control-label">{{trans('home.CUSTOM_SIZE')}}</label>
										<div class="col-lg-9">
											<div class="pull-left">
												<input id="custom_width" data-string="{{$viewvideo->string_Id}}" data-poster="{{$viewvideo->poster}}" data-src="<?= ($viewvideo->video_sd != NULL) ? strip_tags($viewvideo->video_sd) : strip_tags($viewvideo->video_src); ?>" type="text" class="form-control" value="" placeholder="Width" style="width: 100px!important;">
											</div>
											<div class="pull-left m-l-5 m-r-5" style="line-height: 38px;"> × </div>
											<div class="pull-left m-r-15">
												<input id="custom_height" data-string="{{$viewvideo->string_Id}}" data-poster="{{$viewvideo->poster}}" data-src="<?= ($viewvideo->video_sd != NULL) ? strip_tags($viewvideo->video_sd) : strip_tags($viewvideo->video_src); ?>" type="text" class="form-control" value="" placeholder="Height" style="width: 100px!important;">
											</div>
											<div class="pull-left" style="line-height: 38px;">
												({{trans('home.MINIUM')}}: 320 × 180)
											</div>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						@if ($channel_name != NULL)
						<div>
							<div class="m-t-15 overflow-hidden">{{trans('home.CHANNEL')}}:
								<a class="tag" href="{{URL(getLang().'channel')}}/{{$channel_name->id}}/{{$channel_name->post_name}}">{{$channel_name->title_name}}</a>
							</div>
						</div>
						@endif
						@if ($pornstar_name != NULL)
						<div>
							<div class="m-t-15 overflow-hidden">{{trans('home.PORNSTAR')}}:
								<a class="tag" href="{{URL(getLang().'pornstars')}}/{{$pornstar_name->id}}/{{$pornstar_name->post_name}}">{{$pornstar_name->title_name}}</a>
							</div>
						</div>
						@endif
						<div>
							<div class="col-sm-6 col-xs-6" style="padding: 0;">
							@if ($author_post != NULL)
								<div class="m-t-10 overflow-hidden">{{trans('home.BY_AUTHOR')}}:
									<a class="tag" href="<?= $author_link; ?>">{{$author_post->firstname}} {{$author_post->lastname}}</a>
								</div>
							@endif
							</div>
							<div class="col-sm-6 col-xs-6 text-right" style="padding: 0;">
								<div class="hidden-xs">
									{{trans('home.VIEWS')}}: <span class="big-views text-white">{{ !is_null($viewvideo->total_view) ? $viewvideo->total_view : 0 }}</span>
								</div>
								<div class="visible-xs">
									{{trans('home.VIEWS')}}: <span class="big-views-xs text-white">{{ !is_null($viewvideo->total_view) ? $viewvideo->total_view : 0 }}</span>
								</div>
							</div>
						</div>
						<div style="width: 100%; float: left;">
							<div class="col-sm-12 col-xs-12" style="padding: 0; margin-bottom: 10px; margin-top: 10px;">
								<div class="m-t-10 overflow-hidden">{{trans('home.TAGS')}}:
									@foreach ($tag as $result)
									<a class="btn btn-default btn-xs" role="button" href="{{URL(getLang().'search.html?keyword=')}}{{$result}}">
										<span class="glyphicon glyphicon-tag"></span> {{$result}}
									</a>
									@endforeach
								</div>
							</div>
							<div class="col-xs-12 col-sm-12" style="padding: 0; margin-bottom: 10px;">
							@if ($viewvideo->categories_Id != NULL)
								<div class="clearfix"></div>
								<div class="m-t-10 overflow-hidden">{{trans('home.CATEGORY')}}:
									<?= get_categories_list_link($viewvideo->categories_Id); ?>
								</div>
							@endif
							</div>
							<div class="clearfix"></div>
						</div>
						<div>
							<div class="m-t-10 m-b-15" style="padding-top: 10px;">
								<!-- Go to www.addthis.com/dashboard to customize your tools -->
								<div class="addthis_inline_share_toolbox" data-url="{{URL(getLang().'watch')}}/{{$viewvideo->string_Id}}/{{$viewvideo->post_name}}.html" data-img="{{$viewvideo->poster}}" data-title="{{$viewvideo->title_name}}"></div>
								<!-- Go to www.addthis.com/dashboard to customize your tools -->
							</div>
							<div class="clearfix"></div>
						</div>
						<div>
							<div class="m-t-10 m-b-15 text-white">{{$viewvideo->description}}</div>
						</div>
					</div> <!-- End container referent -->
				</div> <!-- End box referent -->

				<h2 >{{trans('home.RELATED_VIDEOS')}}</h2>
				<div class="box-video-related box-boder"> <!-- Box video related -->

					<div class="row videos content-image">
						@foreach($related as $result)
						<?php $rating = VideoHelper::getRating($result->string_Id); ?>
						<div class="col-xs-6 col-sm-4 col-md-3 image-left">
							<div class="col">
								<div class="col_img">
									<span class="hd">HD</span>
									<a href="{{URL(getLang().'watch')}}/{{$result->string_Id."/".$result->post_name}}.html">
										<img data-preview ="{{$result->preview}}"
											data-src="{{$result->poster}}"
											class="js-videoThumbFlip on-error-img img-responsive"
											data-digitsSuffix="{{$result->digitsSuffix}}"
											data-digitsPreffix="{{$result->digitsPreffix}}"
											data-from="{{$result->website}}"
											src="{{$result->poster}}"
											alt="{{$result->title_name}}" />

										<div class="position_text">
											<p class="time_minimute">{{sec2hms($result->duration)}}</p>
										</div>
								</div>
								<h3><a href="{{URL(getLang().'watch')}}/{{$result->string_Id.'/'.$result->post_name}}.html">{{$result->title_name}} </a></h3>
								<span class="titleviews">{{$result->total_view == NULL ? 0: truncate($result->total_view, 26) }} {{trans('home.VIEWS')}}  <span class="titlerating"><i class="fa fa-thumbs-up" aria-hidden="true"></i> {{floor($rating['percent_like'])}}%</span></span>
							</div>
						</div>
						@endforeach
					</div>
				</div><!--End box video related -->

				<div class="box-video-comment"><!-- Box Comment -->
					@if($viewvideo->comment_status === 1 || $viewvideo->comment_status === '1')
					<div class="container-fluid comment">
						<h2 id="countComment">{{$countcomment}} {{trans('home.COMMENTS')}}</h2>

						<div id="comment-msg"></div>
						@if(\Session::has('User'))
						<div class="input-group">
							<input name="comment-text" maxlength="150"  id="comment-text" type="text" value="" class="form-control" placeholder="">
							<span class="input-group-btn">
								<input type="hidden" name="id" value="{{$viewvideo->string_Id}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<button id="send-comment" class="btn btn-signup" type="button">{{trans('home.ADD_COMMENT')}}</button>
							</span>
						</div><!-- /input-group -->
						<span  align="right" class="pull-right pading border_b width-100"><i>{{trans('home.MAX_LENGHT')}}:</i> <i id="show-text-length">150</i></span>
						@else
						<h2>{{trans('home.PLEASE_LOGIN_TO_COMMENT')}}</h2>
						@endif
						<div class="clearfix"></div>
						<div id="result-comment" class="result-comment">
							<ol id="update" class="timeline">
								@if(isset($getcomment))
								{{dumpComments($getcomment)}}
								@include('video.loadmore')
								@else
								{{trans('home.NO_COMMENT')}}
								@endif
							</ol>
							<div id="flash" align="left"></div>

							<div id="commentAction">
								@if($countcomment>0)
								@if($countcomment >4)
								<div align="center"><input id="loadmore"  type="button" style="width:100%;" class="btn btn-load" name="load-more" value="{{trans('home.LOAD_MORE_COMMENTS')}}"></div>
								@else
								<div align="center"><input id="loadmore"  type="button" style="display: none;width: 100%" class="btn btn-load" name="load-more" value="{{trans('home.LOAD_MORE_COMMENTS')}}"></div>
								@endif
								<div align="center"><input id="loadback"  type="button" style="width:100%;" class="btn btn-load" name="loadback" value="{{trans('home.LOAD_BACK')}}"></div>
								@endif
							</div>
						</div>
					</div>
					@endif
				</div><!-- End Box Comment -->

			</div> <!-- End box video -->
			<?php $StandardAdVideo =  StandardAdVideo();?>
			@if(count($StandardAdVideo) > 0)
				@foreach($StandardAdVideo as $key => $result)
					@if($key > 0 && $key == count($StandardAdVideo) - 1) <div class="clearfix"></div>@endif
					@if($result->type === 'upload')
					<div class="col-sm-3 pull-right"><!-- Box advertisement -->
						<div class="ads-here-right" style="/*max-height: 310px;*/ min-height: 50px;">
							@if($key=== 0)<p class="advertisement">{{trans('home.ADVERTISEMENT')}}</p>@endif
							<a target='_new' href='{{$result->return_url}}' title='{{$result->ads_title}}'><img src='{{$result->ads_content}}' style='max-height:350px;'/></a>
						</div>
					</div><!-- End box advertisement -->
					@endif
					@if($result->type === 'script_code')
					<div class="col-sm-3 pull-right"><!-- Box advertisement -->
						<div class="ads-here-right" style="/*max-height: 310px;*/ min-height: 50px;">
							@if($key=== 0)<p class="advertisement">{{trans('home.ADVERTISEMENT')}}</p>@endif
							<div id="AD_ID">{!! $result->script_code !!}</div>
						</div>
					</div><!-- End box advertisement -->
					@endif
					@if($result->type === 'swf')
					<div class="col-sm-3 pull-right"><!-- Box advertisement -->
						<div class="ads-here-right"  style="/*max-height: 310px;*/ min-height: 50px;">
							@if($key=== 0)<p class="advertisement">{{trans('home.ADVERTISEMENT')}}</p>@endif
							<a target='_new' href='{{$result->return_url}}' title='{{$result->ads_title}}'><img src='{{$result->ads_content}}' style='max-height:269px'/></a>
						</div>
					</div><!-- End box advertisement -->
					@endif
				@endforeach
			@else
				<div class="col-sm-3 pull-right"><!-- Box advertisement -->
					<div class="ads-here-right"  style="/*max-height: 310px;*/min-height: 50px;">
						<p class="advertisement">ADVERTISEMENT</p>
						<a target='_new' href='#' title=''><img src='{{asset("public/assets/images/ads-here.jpg")}}' /></a>
					</div>
				</div><!-- End box advertisement -->
			@endif
		</div><!-- End row -->
	</div><!-- End container-fuild -->
</div><!-- End main-content -->

<div id="subscribe" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="panel panel-primary">
			<div class="panel-heading">{{trans('home.WARNING')}}!:</div>
			<div class="panel-body">
				<p id="messages">{{trans('home.PLEASE_LOGIN_OR_SIGNUP_FOR_USE')}}</p>
			</div>
		</div>
	</div>
</div>
<div id="modal-msg-box" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="panel panel-primary">
			<div class="panel-heading">{{trans('home.SEND_MESSAGE')}}</div>
			<div class="panel-body">
				<div id="msg-all-center"></div>
				<form >
					<div class="col-md-12">
						<div class="form-group">
							<label for="emails">{{trans('home.EMAIL')}}: </label>
							<div id="email-msg-alert" class="alert-error"></div>
							<input type="email" class="form-control" value="" id="emails" name="msg-email" placeholder="Your email here !" >
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="lastname">{{trans('home.MESSAGE')}}: </label>
							<div id="content-msg-alert" class="alert-error"></div>
							<textarea class="form-control" rows="5" name="msg-content">
							</textarea>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="col-md-6 pull-right">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="button" name="send-msg"  id="send-msg" value="{{trans('home.SEND')}}" class="btn btn-signup pull-right">
						<input type="button" data-dismiss="modal" id="cancel" class="btn btn-signup pull-right" style="margin-right: 5px;" value="{{trans('home.CANCEL')}}">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="modal_show_subscribe" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="panel panel-primary">
			<div class="panel-heading">{{trans('home.SUBSCRIBE')}} {{$viewvideo->title_name}}</div>
			<div class="panel-body">
				<p id="modal_content_subscribe"></p>
			</div>
			<div class="panel-footer">
				<div class="input-group" style="width: 100%;">
					<input type="button" data-dismiss="modal" class="btn btn-signup pull-right" style="margin-right: 5px;" value="{{trans('home.CLOSE')}}">
				</div>
			</div>
		</div>
	</div>
</div>
<div id="modal_show_favorite" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="panel panel-primary">
			<div class="panel-heading">{{trans('home.FAVOURITE')}}</div>
			<div class="panel-body">
				<p id="modal_content_favorite"></p>
			</div>
			<div class="panel-footer">
				<div class="input-group" style="width: 100%;">
					<input type="button" data-dismiss="modal" class="btn btn-signup pull-right" style="margin-right: 5px;" value="{{trans('home.CLOSE')}}">
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('scriptFooter')
	<script src="{{URL('public/assets/js/player.js?v=2')}}" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-599523cad3f36191"></script>
	<div id="fb-root"></div>
	<script type="text/javascript">
		(function (d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id))
				return;
			js = d.createElement(s);
			js.id = id;
			js.src = "//connect.facebook.net/en/sdk.js#xfbml=1&version=v2.4";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));

		$(document).ready(function () {

			$(document).on('click', '#send-comment', function (e) {
				e.preventDefault();
				var comment = $("#comment-text").val();

				if (comment == '') {
					$('#comment-msg').html('<div class="alert alert-danger"><span  class="glyphicon glyphicon-remove"></span><strong> Invalid Comment cannot be blank</strong></div>').fadeIn().delay(10000).fadeOut();
					$("#comment-text").focus();
				} else {
					$("#flash").show();
					$("#flash").fadeIn(400).html('<img src="{{URL("public/assets/images/result_loading.gif")}}" align="absmiddle">&nbsp;<span class="loading">Loading Comment...</span>');

					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
						}
					});
					$.ajax({
						url: "{{URL(getLang().'comment.html')}}",
						type: "POST",
						data: {

							'comment_text': $('input[name=comment-text]').val(),
							'_token': $('input[name=_token]').val(),
							'id': $('input[name=id]').val()
						},
						success: function (data) {
							reloadComment();
							$("ol#update li").fadeIn("slow");
							document.getElementById('comment-text').value = '';
							$("#comment-text").focus();
							$("#flash").hide();
						}, error: function (res) {
							if (res.status == 401) {
								$('#myModal').modal('show');
								$("#flash").hide();
							}
						}
					});
				}
			});

			$("#comment-text").keypress(function (event) {
				if (event.which == 13 || event.keyCode == 13) {
					var comment = $(this).val();

					if (comment == '') {
						$('#comment-msg').html('<div class="alert alert-danger"><span  class="glyphicon glyphicon-remove"></span><strong> Invalid Comment cannot be blank</strong></div>').fadeIn().delay(10000).fadeOut();
						$(this).focus();
					} else {
						$("#flash").show();
						$("#flash").fadeIn(400).html('<img src="{{URL("public/assets/images/result_loading.gif")}}" align="absmiddle">&nbsp;<span class="loading">Loading Comment...</span>');

						$.ajaxSetup({
							headers: {
								'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
							}
						});
						$.ajax({
							url: "{{URL(getLang().'comment.html')}}",
							type: "POST",
							data: {
								'comment_text': $('input[name=comment-text]').val(),
								'_token': $('input[name=_token]').val(),
								'id': $('input[name=id]').val()
							},
							success: function (data) {
								reloadComment();
								$("ol#update li").fadeIn("slow");
								document.getElementById('comment-text').value = '';
								$("#comment-text").focus();
								$("#flash").hide();
							}, error: function (res) {
								if (res.status == 401) {
									$('#myModal').modal('show');
									$("#flash").hide();
								}
							}
						});
					}
				}
			});

			var page = 1;
			var currentPage = 1;
			$('#testload').hide();
			$('#loadback').hide();
			$(document).on('click', '#loadmore', function () {
				currentPage = page;
				page = page + 1
				// console.log(page);
				getpagecomment(page);
			});
			function reloadComment() {
				$.ajax({
					url: "{{URL(getLang())}}/loadmore/{{$viewvideo->string_Id}}.html?page=" + 1,
					success: function (data) {
						var commentObject = $.parseJSON(data.commentObject);
						$("ol#update").empty().append(data.html);
						if (commentObject.total > 4) {
							if (!$("#loadmore").length) {
								$("#commentAction").append(' <div align="center"><input id="loadmore"  type="button" style="width:100%;" class="btn btn-load" name="load-more" value="Load More Comment"></div>');
							}
							$('#loadmore').show();
						}
						$("#countComment").html(commentObject.total + ' ' + 'comments')
					}
				})
				$('#loadback').hide();

			}
			function getpagecomment(page) {

				$.ajax({
					url: "{{URL(getLang())}}/loadmore/{{$viewvideo->string_Id}}.html?page=" + page,
					success: function (data) {
						var commentObject = $.parseJSON(data.commentObject);
						if (currentPage == commentObject.last_page) {
							$("#loadmore").hide();
						} else {
							$("ol#update").empty().append(data.html);
						}
					},
					beforeSend: function () {
						$('#flash').html("<img src='{{URL('public/assets/images/result_loading.gif')}}'/>").show();
					},
					complete: function ()
					{
						$('testload').fadeIn("slow");
						$('#flash').html("<img src='{{URL('public/assets/images/result_loading.gif')}}'/>").hide();
					}
				})
			}

			$('#testload').hide();
			$(document).on('click', '#loadback', function () {
				page--;
				getbackpagecomment(page);
				if (page == 1) {
					$('#loadmore').show();
					$('#loadback').hide();
				}

			});
			function getbackpagecomment(page) {
				$.ajax({
					url: "{{URL(getLang())}}/loadmore/{{$viewvideo->string_Id}}.html?page=" + page,
					success: function (data) {
						$("ol#update").empty().append(data.html);
					},
					beforeSend: function () {
						$('#flash').html("<img src='{{URL('public/assets/images/result_loading.gif')}}'/>").show();
					},
					complete: function () {
						$('testload').fadeIn("slow");
						$('#flash').html("<img src='{{URL('public/assets/images/result_loading.gif')}}'/>").hide();
					}
				});
			}

			$('.codo-player-container').append('<div class="ads"></div>');
			$(document).on('click', '#download a', function (e) {
				e.preventDefault();
				window.open('{{URL(getLang())}}/{{$viewvideo->post_name}}.{{$viewvideo->string_Id}}/download.html')
			})
			$(document).on('click', '#subscribe a', function (e) {
				e.preventDefault();
				$.ajax({
					url: "{{URL(getLang())}}/{{$viewvideo->post_name}}.{{$viewvideo->string_Id}}/subscribe.html",
					success: function (data) {
						if (data == "Channel not found !") {
							$('#modal_content_subscribe').empty().append(data);
							$('#modal_show_subscribe').modal('show');
						} else {
							$('#modal_content_subscribe').empty().append(data);
							$('#modal_show_subscribe').modal('show');
							$('#change_subscribed').empty().text('Subscribed')
						}
					}
				});

			});
			$(document).on('click', '#favorite a', function (e) {
				e.preventDefault();
				$.ajax({
					url: "{{URL(getLang())}}/{{$viewvideo->post_name}}.{{$viewvideo->string_Id}}/favorite.html",
					dataType: 'json',
					success: function (data) {
						if (data) {
							$('#modal_content_favorite').empty().append(data.message);
							$('#modal_show_favorite').modal('show');
							if (data.number != undefined) {
								$('#change_favorited').empty().text(data.number);
								if(!$('#favorite i').hasClass("pink"))
									$('#favorite i').addClass("pink");
							}
						}
					}
				});
			});
		});
		var firstLoad = true;
		var htmlAds = '{!!\App\Models\VideoTextAdsModel::get_list()!!}';
		$(window).load(function () {
			var length = $('#comment-text').attr('maxlength');
			$('#comment-text').keyup(function () {
				var current = $(this).val();
				var count_length = length - current.length;
				$('#show-text-length').text(count_length);
			});
		});

		function showlogin() {
			$('#myModal').modal('show');
		}
	</script>
@endsection
