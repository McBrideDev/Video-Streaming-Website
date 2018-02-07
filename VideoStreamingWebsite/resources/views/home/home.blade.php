<?php
use App\Helper\VideoHelper;
?>
@extends('master-frontend')
@section('title', 'Home')
@section('content')
<div class="main-content">
	<div class="container-fluid">
		@if(isset($msglogin))
			{{$msglogin}}
		@endif

		@if(count($watch_now) > 0 || count($indexnew) > 0)
		<h2>{{trans('home.WATCHING_NOWS')}}</h2>
		<div class="row content-image videos">
			@if(count($watch_now)>0)
				<!-- Watched Now -->
				<?php $itemsWatched =1; ?>
				@foreach($watch_now as $result)
					<?php $rating = VideoHelper::getRating($result->string_Id); ?>
					<div class="col-xs-6 col-sm-3 col-md-2 image-left">
						<div class="col">
							<div class="col_img">
								<span class="hd">HD</span>
								<a href="{{URL(getLang().'watch')}}/{{$result->string_Id.'/'.$result->post_name}}.html">
									<img data-preview ="{{$result->preview}}"
										data-src="{{$result->poster}}"
										class="js-videoThumbFlip on-error-img img-responsive" data-digitsSuffix="{{$result->digitsSuffix}}"
										data-digitsPreffix="{{$result->digitsPreffix}}"
										data-from="{{$result->website}}"
										src="{{$result->poster}}"
										alt="{{$result->title_name}}" />
								</a>
								<div class="position_text">
									<p class="time_minimute">{{sec2hms($result->duration)}}</p>
								</div>
							</div>
							<h3> <a href="{{URL(getLang().'watch')}}/{{$result->string_Id.'/'.$result->post_name}}.html">{{$result->title_name}}</a> </h3>
							<span class="titleviews">
								{{$result->total_view == NULL ? 0: truncate($result->total_view, 26) }} {{trans('home.VIEWS')}}
								<span class="titlerating"><i class="fa fa-thumbs-up" aria-hidden="true"></i> {{floor($rating['percent_like'])}}%</span>
							</span>
						</div>
					</div>
					<?php if($itemsWatched==3) { ?>
					<div class="col-xs-6 col-sm-4 col-md-4 pull-right hidden-xs hidden-sm">
						<div class="ads-here-right">
							<?= StandardAdHome();?>
						</div>
					</div>
					<div class="clearfix visible-xs hidden-xs hidden-sm"></div>
					<?php }?>
					<?php if($itemsWatched==4) {?>
					<div class="clearfix visible-sm hidden-xs hidden-sm"></div>
					<?php }?>
					<?php if($itemsWatched==8) {?>
					<div class="clearfix hidden-xs hidden-sm"></div>
					<?php }?>

					<?php $itemsWatched++;?>
				@endforeach
			@else
				<!-- Featured -->
				<?php $itemsFeatured =1; ?>
				@foreach($indexnew as $resultnew)
					<?php $rating = VideoHelper::getRating($resultnew->string_Id); ?>
					<div class="col-xs-6 col-sm-3 col-md-2 image-left">
						<div class="col">
							<div class="col_img">
								<span class="hd">HD</span>
								<a href="{{URL(getLang().'watch')}}/{{$resultnew->string_Id.'/'.$resultnew->post_name}}.html">
									@if ($resultnew->poster == NULL)
									<img src="{{URL('public/assets/images/no-image.jpg')}}" alt="{{$resultnew->title_name}}" class="img-responsive" height="145" />
									@else
									<img data-preview ="{{$resultnew->preview}}"
										data-src="{{$resultnew->poster}}"
										class="js-videoThumbFlip on-error-img img-responsive"
										data-digitsSuffix="{{$resultnew->digitsSuffix}}"
										data-digitsPreffix="{{$resultnew->digitsPreffix}}"
										data-from="{{$resultnew->website}}"
										src="{{$resultnew->poster}}"
										alt="{{$resultnew->title_name}}" />
									@endif
								</a>
								<div class="position_text"> <p class="time_minimute">{{sec2hms($resultnew->duration)}}</p> </div>
							</div>
							<h3>
								<a href="{{URL(getLang().'watch')}}/{{$resultnew->string_Id.'/'.$resultnew->post_name}}.html">{{$resultnew->title_name}}</a>
							</h3>
							<span class="titleviews">
								{{$resultnew->total_view == NULL ? 0: truncate($resultnew->total_view, 26) }} {{trans('home.VIEWS')}}
								<span class="titlerating">
									<i class="fa fa-thumbs-up" aria-hidden="true"></i> {{floor($rating['percent_like'])}}%
								</span>
							</span>
						</div>
					</div>
					<?php if($itemsFeatured==3) { ?>
					<div class="col-xs-6 col-sm-4 col-md-4 pull-right hidden-xs hidden-sm">
						<div class="ads-here-right">
							<?= StandardAdHome();?>
						</div>
					</div>
					<div class="clearfix hidden-xs hidden-sm visible-xs "></div>
					<?php }?>
					<?php if($itemsFeatured==4) {?>
					<div class="clearfix hidden-xs hidden-sm visible-sm"></div>
					<?php }?>
					<?php if($itemsFeatured==8) {?>
					<div class="clearfix hidden-xs hidden-sm"></div>
					<?php }?>

					<?php $itemsFeatured++;?>
				@endforeach
			@endif
		</div>
		@endif

		@if(!empty($today))
			<div class="titile-cate">
				<?= get_title_datetime();?>
			</div>
			<div class="row content-image videos">
				@foreach($today as $resulttoday)
					<?php $rating = VideoHelper::getRating($resulttoday->string_Id); ?>
					<div class="col-xs-6 col-sm-3 col-md-2 image-left">
						<div class="col">
							<div class="col_img">
								<span class="hd">HD</span>
								<a href="{{URL(getLang().'watch')}}/{{$resulttoday->string_Id."/".$resulttoday->post_name}}.html">
									@if ($resulttoday->poster == NULL)
									<img src="{{URL('public/assets/images/no-image.jpg')}}" alt="{{$resulttoday->title_name}}" class="img-responsive" height="145" />
									@else
									<img data-preview ="{{$resulttoday->preview}}"
										data-src="{{$resulttoday->poster}}"
										class="js-videoThumbFlip on-error-img img-responsive"
										data-digitsSuffix="{{$resulttoday->digitsSuffix}}"
										data-digitsPreffix="{{$resulttoday->digitsPreffix}}"
										data-from="{{$resulttoday->website}}"
										src="{{$resulttoday->poster}}"
										alt="{{$resulttoday->title_name}}" />
									@endif
								</a>
								<div class="position_text">
									<p class="time_minimute">{{sec2hms($resulttoday->duration)}}</p>
								</div>
							</div>
							<h3> <a href="{{URL(getLang().'watch')}}/{{$resulttoday->string_Id.'/'.$resulttoday->post_name}}.html">{{$resulttoday->title_name}}</a> </h3>
							<div class="titleviews">
								{{$resulttoday->total_view == NULL ? 0: truncate($resulttoday->total_view, 26) }} {{trans('home.VIEWS')}}
								<span class="titlerating"><i class="fa fa-thumbs-up" aria-hidden="true"></i> {{floor($rating['percent_like'])}}%</span>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		@endif

		@if(!empty($todayRating))
			<div class="titile-cate">
				{{trans('home.MOST_VIEWED_VIDEO')}}
				<ul>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">{{trans('home.VIEWS')}}<span class="caret"></span></a>
						<ul class="dropdown-menu" id="video-filter">
							<li class="active" ><a href="javascript:void(0)" data-value="views">{{trans('home.VIEWS')}}</a></li>
							<li ><a href="javascript:void(0)" data-value="rating-video">{{trans('home.RATING')}}</a></li>
							<li ><a href="javascript:void(0)" data-value="duration">{{trans('home.DURATIONS')}}</a></li>
							<li ><a href="javascript:void(0)" data-value="date">{{trans('home.DATE')}}</a></li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="row content-image videos">
				<?php $items=1; ?>
				@foreach($todayRating as $resulttoday)
					<?php $rating =  VideoHelper::getRating($resulttoday->string_Id); ?>
					<div class="col-xs-6 col-sm-3 col-md-2 image-left">
						<div class="col">
							<div class="col_img">
								<span class="hd">HD</span>
								<a href="{{URL(getLang().'watch')}}/{{$resulttoday->string_Id."/".$resulttoday->post_name}}.html">
									@if ($resulttoday->poster == NULL)
									<img src="{{URL('public/assets/images/no-image.jpg')}}" alt="{{$resulttoday->title_name}}" class="img-responsive" height="145" />
									@else
									<img data-preview ="{{$resulttoday->preview}}"
										data-src="{{$resulttoday->poster}}"
										class="js-videoThumbFlip on-error-img img-responsive"
										data-digitsSuffix="{{$resulttoday->digitsSuffix}}"
										data-digitsPreffix="{{$resulttoday->digitsPreffix}}"
										data-from="{{$resulttoday->website}}"
										src="{{$resulttoday->poster}}"
										alt="{{$resulttoday->title_name}}" />
									@endif
								</a>
								<div class="position_text">
									<p class="icon-like"></p>
									<p class="time_minimute">{{sec2hms($resulttoday->duration)}}</p>
								</div>
							</div>
							<h3> <a href="{{URL(getLang().'watch')}}/{{$resulttoday->string_Id.'/'.$resulttoday->post_name}}.html">{{$resulttoday->title_name}}</a> </h3>
							<span class="titleviews">
								{{$resulttoday->total_view == NULL ? 0: truncate($resulttoday->total_view, 26) }} {{trans('home.VIEWS')}}
								<span class="titlerating"><i class="fa fa-thumbs-up" aria-hidden="true"></i> {{floor($rating['percent_like'])}}%</span>
							</span>
						</div>
					</div>
					<?php if($items==3) { ?>
					<div class="col-xs-6 col-sm-4 col-md-4 pull-right hidden-xs hidden-sm">
						<div class="ads-here-right">
							<?= StandardAdHome();?>
						</div>
					</div>
					<div class="clearfix hidden-xs hidden-sm visible-xs "></div>
					<?php }?>
					<?php if($items==4) {?>
					<div class="clearfix hidden-xs hidden-sm visible-sm"></div>
					<?php }?>
					<?php if($items==8) {?>
					<div class="clearfix hidden-xs hidden-sm"></div>
					<?php }?>

					<?php $items++;?>
				@endforeach
			</div>
		@endif

		@if($recommentVideos!=NULL)
			@foreach($recommentVideos as $result)
				<h2>{{trans('home.RECOMMENDED_CATEGORY_FOR_YOU')}}
					<a style="text-decoration: none" href="{{$result['category']['url']}}">
						<span>-{{$result['category']['title']}}</span>
					</a>
				</h2>
				@if(!empty($result['videos']))
				<div class="row content-image videos">
					@foreach($result['videos'] as $r)
						<div class="col-xs-6 col-sm-3 col-md-2 image-left">
							<div class="col">
								<div class="col_img">
									<span class="hd">HD</span>
									<a href="{{URL(getLang().'watch')}}/{{$r['string_Id'].'/'.$r['post_name']}}.html">
										@if ($r['poster'] == NULL)
										<img src="{{URL('public/assets/images/no-image.jpg')}}" alt="{{$r['title_name']}}" class="img-responsive" height="145" />
										@else
										<img data-preview ="{{$r['preview']}}"
											data-src="{{$r['poster']}}"
											class="js-videoThumbFlip on-error-img img-responsive"
											data-digitsSuffix="{{$r['digitsSuffix']}}"
											data-digitsPreffix="{{$r['digitsPreffix']}}"
											data-from="{{$r['website']}}"
											src="{{$r['poster']}}"
											alt="{{$r['title_name']}}" />
										@endif
									</a>
									<div class="position_text">
										<p class="icon-like"></p>
										<p class="time_minimute">{{sec2hms($r['duration'])}}</p>
									</div>
								</div>
								<h3> <a href="{{URL(getLang().'watch')}}/{{$r['string_Id'].'/'.$r['post_name']}}.html">{{$r['title_name']}}</a> </h3>
								<span class="titleviews">
									{{$r['total_view'] ===NULL ? 0: truncate($r['total_view'], 26) }} {{trans('home.VIEWS')}}
									<span class="titlerating"><i class="fa fa-thumbs-up" aria-hidden="true"></i> {{floor($r['rating']['percent_like'])}}%</span>
								</span>
							</div>
						</div>
					@endforeach
				</div>
				@endif
			@endforeach
		@endif

		@if(!empty($today))
		<div class="page_navigation">
			 {!!$today->render()!!}
		</div>
		@endif
	</div>
</div>
@endsection
