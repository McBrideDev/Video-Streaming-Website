<?php
use App\Helper\VideoHelper;
?>
@extends('master-frontend')
@section('title', trans('home.MOST_VIEWED_VIDEO'))
@section('content')
<div class="main-content categories_page">
	<div class="container-fluid top-rate ">
		<div class="row">
			<div class="col-md-12 ">
				<div class="titile-cate">
					{{trans('home.MOST_VIEWED_VIDEO')}}
					<div class="visible-xs clearfix"></div>
					<!-- duration -->
					<ul style="width:200px" class="hidden-xs">
						<li class="dropdown">
							<a href="#" id="set-view-time" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true" data-time="all" ><i id="txt-time-view">{{trans('home.ALL_DURATION')}}</i><span class="caret"></span></a>
							<ul id="chose-time-view" class="dropdown-menu">
								<li class="time-view hidden-xs" ><a role="all" full-text="{{trans('home.ALL_DURATION')}}" href="javascript:void(0);">{{trans('home.ALL_DURATION')}}</a></li>
								<li class="time-view hidden-xs" ><a role="1-3" full-text="{{trans('home.SHORT_VIDEOS')}} (1-3{{trans('home.MIN')}})" href="javascript:void(0);">{{trans('home.SHORT_VIDEOS')}} (1-3{{trans('home.MIN')}})</a></li>
								<li class="time-view hidden-xs" ><a role="3-10" full-text="{{trans('home.MEDIUM_VIDEOS')}} (3-10{{trans('home.MIN')}})" href="javascript:void(0);">{{trans('home.MEDIUM_VIDEOS')}} (3-10{{trans('home.MIN')}})</a></li>
								<li class="time-view hidden-xs" ><a role="10+" full-text="{{trans('home.LONG_VIDEOS')}} (+10{{trans('home.MIN')}})" href="javascript:void(0);">{{trans('home.LONG_VIDEOS')}} (+10{{trans('home.MIN')}})</a></li>

							</ul>
						</li>
					</ul>
					<ul class="visible-xs" style="">
						<li class="dropdown">
							<a id="set-view-time" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true" data-time="all" ><i id="txt-time-view">{{trans('home.ALL_DURATION')}}</i><span class="caret"></span></a>
							<ul id="chose-time-view" class="dropdown-menu" style="min-width:110px !important; width:110px ">
								<li class="time-view visible-xs" ><a role="all" full-text="{{trans('home.ALL')}}" href="javascript:void(0);">{{trans('home.ALL_DURATION')}}</a></li>
								<li class="time-view visible-xs" ><a role="1-3" full-text="1-3{{trans('home.MIN')}}" href="javascript:void(0);">{{trans('home.SHORT_VIDEOS')}} (1-3{{trans('home.MIN')}})</a></li>
								<li class="time-view visible-xs" ><a role="3-10" full-text="3-10{{trans('home.MIN')}}" href="javascript:void(0);">{{trans('home.MEDIUM_VIDEOS')}} (3-10{{trans('home.MIN')}})</a></li>
								<li class="time-view visible-xs" ><a role="10+" full-text="+10{{trans('home.MIN')}}" href="javascript:void(0);">{{trans('home.LONG_VIDEOS')}} (+10{{trans('home.MIN')}})</a></li>
							</ul>
						</li>
					</ul>
					<!--end duration -->
					<!--Date -->
					<ul class="hidden-xs">
						<li class="dropdown">
							<a href="#" id="date-sort-view" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true" data-date="all" ><i id="txt-date-view">{{trans('home.ALL_TIMES')}}</i><span class="caret"></span></a>
							<ul id="chose-date-view" class="dropdown-menu">
								<li class="date-sort-view hidden-xs" ><a role="all" full-text="{{trans('home.ALL_TIMES')}}" href="javascript:void(0);">{{trans('home.ALL_TIMES')}}</a></li>
								<li class="date-sort-view hidden-xs" ><a role="today" full-text="{{trans('home.TO_DAY')}}" href="javascript:void(0);">Rated {{trans('home.TO_DAY')}}</a></li>
								<li class="date-sort-view hidden-xs" ><a role="week" full-text="{{trans('home.THIS_WEEK')}}" href="javascript:void(0);">Rated {{trans('home.THIS_WEEK')}}</a></li>
								<li class="date-sort-view hidden-xs" ><a role="month" full-text="{{trans('home.THIS_MONTH')}}" href="javascript:void(0);">Rated {{trans('home.THIS_MONTH')}}</a></li>
							</ul>
						</li>
					</ul>
					<ul class="visible-xs"  style="">
						<li class="dropdown">
							<a href="#" id="date-sort-view" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true" data-date="all" ><i id="txt-date-view">{{trans('home.ALL_TIMES')}}</i><span class="caret"></span></a>
							<ul id="chose-date-view" class="dropdown-menu" style="min-width:110px !important; width:110px ">
								<li class="date-sort-view hidden-xs" ><a role="all" full-text="{{trans('home.ALL')}}" href="javascript:void(0);">{{trans('home.ALL')}}</a></li>
								<li class="date-sort-view visible-xs" ><a role="today" full-text="{{trans('home.TO_DAY')}}" href="javascript:void(0);">{{trans('home.TO_DAY')}}</a></li>
								<li class="date-sort-view visible-xs" ><a role="week" full-text="{{trans('home.THIS_WEEK')}}" href="javascript:void(0);">{{trans('home.THIS_WEEK')}}</a></li>
								<li class="date-sort-view visible-xs" ><a role="month" full-text="{{trans('home.THIS_MONTH')}}" href="javascript:void(0);">{{trans('home.THIS_MONTH')}}</a></li>
							</ul>
						</li>
					</ul>
					<!-- endDate -->
				</div>
			</div>
			<div class="visible-x clearfix" style="margin-bottom: 15px;"></div>
			<div class="col-md-12" id="most-view-fillter">
				<div class="content-image videos" >
					<?php $items = 1; ?>
					@foreach($mostview as $result)
					<?php $rating = VideoHelper::getRating($result->string_Id); ?>
					<div class="col-xs-6 col-sm-4 col-md-2 image-left">
						<div class="col">
							<div class="col_img">
								<span class="hd">HD</span>
								<a href="{{URL(getLang().'watch')}}/{{$result->string_Id."/".$result->post_name}}.html">
									<img data-preview ="{{$result->preview}}" data-src="{{$result->poster}}" class="js-videoThumbFlip img-responsive on-error-img" data-digitsSuffix="{{$result->digitsSuffix}}" data-digitsPreffix="{{$result->digitsPreffix}}" data-from="{{$result->website}}" src="{{$result->poster}}" alt="{{$result->title_name}}" />
								</a>
								<div class="position_text">
									<p class="time_minimute">{{sec2hms($result->duration)}}</p>
								</div>
							</div>
							<h3>
								<a href="{{URL(getLang().'watch')}}/{{$result->string_Id."/".$result->post_name}}.html">{{$result->title_name}}</a>
							</h3>
							<span class="titleviews">{{$result->total_view ===NULL ? 0: truncate($result->total_view, 26) }} {{trans('home.VIEWS')}}  <span class="titlerating"><i class="fa fa-thumbs-up" aria-hidden="true"></i> {{floor($rating['percent_like'])}}%</span></span>

						</div>
					</div>
					<?php if ($items == 3) { ?>
					  <div class="col-xs-6 col-sm-4 col-md-4 image-right pull-right hidden-xs">
						  <div class="ads-here-right">
							  <p class="advertisement">{{trans('home.ADVERTISEMENT')}}</p>
							  <?= StandardAdMostView() ?>
						  </div>
					  </div>
					  <div class="clearfix visible-xs"></div>
					  <div class="col-sm-6 image-left visible-xs">
						  <div class="ads-here-right">
							  <p class="advertisement">{{trans('home.ADVERTISEMENT')}}</p>
							  <?= StandardAdMostView() ?>
						  </div>
					  </div>
					  <div class="clearfix visible-xs"></div>
					<?php } ?>
					<?php if ($items == 8) { ?><div class="clearfix"></div><?php } ?>
					<?php $items++; ?>
					@endforeach
				</div>

				<div class="clearfix"></div>

				<div id="page_mostview" class="page_navigation">
					{!!$mostview->render()!!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
