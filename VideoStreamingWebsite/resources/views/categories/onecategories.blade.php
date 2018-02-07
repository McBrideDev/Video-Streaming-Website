<?php
use App\Helper\VideoHelper;
?>
@extends('master-frontend')
@section('title', trans("home.CATEGORY"))
@section('content')
<div class="main-content categories_page">
	<div class="container-fluid pad-l-r-50">
		<div class="row">
			<div class="col-xs-12 col-sm-4 col-md-3 view_cat">
				<div class="view-cat-col">
					<h2><center>{{trans('home.CATEGORY')}}</center></h2>
					<ul>
						@foreach($categories as $result)
						<li><a href="{{URL(getLang().'categories/')}}/{{$result->id}}.{{$result->post_name}}.html">{{$result->title_name}}</a></li>
						@endforeach
					</ul>
				</div>
			</div>
			<div class="col-xs-12 col-sm-8 col-md-9 right_content image-left pad-l-r-videoBox">
				<div class="row">
					<div class="titile-cate">
						{{$onecategoriesdetail->title_name}} {{trans('home.VIDEOS')}}
						<div class=" visible-xs clearfix"></div>
						<ul class="hidden-xs" >
							<li class="dropdown">
								<a href="#" id="set-time-cat" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true" data-time="all" ><?= isset($filter_time_lg) ? '' . $filter_time_lg . '<span class="caret">' : '' . trans("home.ALL_DURATION") . '<span class="caret">' ?></span></a>
								<ul id="chose-time-cat" class="dropdown-menu">
									<li class="time hidden-xs" ><a role="all" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" full-text="{{trans('home.ALL_DURATION')}}" href="javascript:void(0);">{{trans("home.ALL_DURATION")}}</a></li>
									<li class="time hidden-xs" ><a role="1-3" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" full-text="{{trans('home.SHORT_VIDEOS')}} (1-3min)" href="javascript:void(0);">{{trans('home.SHORT_VIDEOS')}} (1-3min)</a></li>
									<li class="time hidden-xs" ><a role="3-10" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" full-text="{{trans('home.MEDIUM_VIDEOS')}} (3-10min)" href="javascript:void(0);">{{trans('home.MEDIUM_VIDEOS')}} (3-10min)</a></li>
									<li class="time hidden-xs" ><a role="10+" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" full-text="{{trans('home.LONG_VIDEOS')}} (+10min)" href="javascript:void(0);">{{trans('home.LONG_VIDEOS')}} (+10min)</a></li>

								</ul>
							</li>
						</ul>
						<ul class="visible-xs" style=" position:relative; top:10px; margin-bottom: 15px;">
							<li class="dropdown">
								<a href="#" id="set-time-cat" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true" data-time="all" ><?= isset($filter_time_xs) ? '' . $filter_time_xs . '<span class="caret">' : ' ' . trans("home.ALL_DURATION") . '<span class="caret">' ?></span></a>
								<ul id="chose-time-cat" class="dropdown-menu" style="min-width:110px !important; width:110px ">
									<li class="time visible-xs" ><a role="all" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" full-text="{{trans('home.ALL_DURATION')}} " href="javascript:void(0);">{{trans("home.ALL_DURATION")}}</a></li>
									<li class="time visible-xs" ><a role="1-3" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" full-text="{{trans('home.SHORT_VIDEOS')}} (1-3min)" href="javascript:void(0);">{{trans('home.SHORT_VIDEOS')}} (1-3min)</a></li>
									<li class="time visible-xs" ><a role="3-10" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" full-text="{{trans('home.MEDIUM_VIDEOS')}} (3-10min)" href="javascript:void(0);">{{trans('home.MEDIUM_VIDEOS')}} (3-10min)</a></li>
									<li class="time visible-xs" ><a role="10+" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" full-text="{{trans('home.LONG_VIDEOS')}} (+10min)" href="javascript:void(0);">{{trans('home.LONG_VIDEOS')}} (+10min)</a></li>
								</ul>
							</li>
						</ul>
						<ul style="" class="hidden-xs">
							<li class="dropdown">
								<a href="#" class="hidden-xs dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><?= isset($filter_title_lg) ? '' . $filter_title_lg . '<span class="caret">' : ' ' . trans("home.NEWEST_VIDEOS") . '<span class="caret">' ?></span></a>
								<ul class="dropdown-menu">
									<li data-action="new-video" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" class="hidden-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-arrow-up"></i> {{trans("home.NEWEST_VIDEOS")}}</a></li>

									<li data-action="most-favorited" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" class="hidden-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-thumbs-o-up"></i> {{trans("home.MOST_FAVORITED")}}</a></li>

									<li data-action="most-rated" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" class="hidden-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-star"></i> {{trans("home.MOST_RATED")}}</a></li>

									<li data-action="most-viewed" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" class="hidden-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-line-chart"></i> {{trans('home.MOST_VIEWED')}}</a></li>

									<li data-action="most-commented" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" class="hidden-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-comments-o"></i> {{trans('home.MOST_COMMENTED')}}</a></li>

								</ul>
							</li>
						</ul>
						<ul style=" position:relative; top:10px; margin-bottom: 15px;" class="visible-xs">
							<li class="dropdown">
								<a href="#" class="visible-xs dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><?= isset($filter_title_xs) ? '' . $filter_title_xs . '<span class="caret">' : ' ' . trans("home.NEWEST_VIDEOS") . '<span class="caret">' ?></a>
								<ul class="dropdown-menu">

									<li data-action="new-video" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" class="visible-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-arrow-up"></i> {{trans("home.MOST_FAVORITED")}}</a></li>

									<li data-action="most-favorited" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" class="visible-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-thumbs-o-up"></i> {{trans("home.MOST_FAVORITED")}}</a></li>

									<li data-action="most-rated" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" class="visible-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-star"></i> {{trans("home.MOST_RATED")}}</a></li>

									<li data-action="most-viewed" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" class="visible-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-line-chart"></i> {{trans('home.MOST_VIEWED')}}</a></li>

									<li data-action="most-commented" data-name="{{$onecategoriesdetail->post_name}}" data-categories="{{$onecategoriesdetail->id}}" class="visible-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-comments-o"></i> {{trans('home.MOST_COMMENTED')}}</a></li>
								</ul>
							</li>
						</ul>
						<input type="hidden" id="hidden-action" data-time="<?= isset($hidden_time) ? $hidden_time : 'all' ?>" data-action="<?= isset($hidden_action) ? $hidden_action : 'new-video' ?>">
					</div>
					<div>
						<div class="visible-xs clearfix" style="margin-bottom: 15px; "></div>
						<div class=" videos" id="categories-loading">
							<?= isset($msg) ? $msg : '' ?>
							@if(isset($videoin))
							@foreach($videoin as $result)
							<?php $rating = VideoHelper::getRating($result->string_Id); ?>
							<div class="col-xs-6 col-md-3" style="margin-bottom: 10px;">
								<div class="col">
									<div class="col_img text-center">
										<span class="hd">HD</span>
										<a href="{{URL(getLang().'watch')}}/{{$result->string_Id."/".$result->post_name}}.html">
											<?php if ($result->poster == NULL) { ?>
												<img src="{{URL('public/assets/images/no-image.jpg')}}" alt="{{$result->title_name}}" class="img-responsive" height="145" />
											<?php } else { ?>
												<img data-preview ="{{$result->preview}}" data-src="{{$result->poster}}" class="js-videoThumbFlip img-responsive on-error-img" data-digitsSuffix="{{$result->digitsSuffix}}" data-digitsPreffix="{{$result->digitsPreffix}}" data-from="{{$result->website}}" src="{{$result->poster}}" alt="{{$result->title_name}}"/>
											<?php } ?>
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

							@endforeach
							@endif
							<div class="clearfix"></div>
							@if(isset($videoin))
							<div class="page_navigation">
								{!!$videoin->render()!!}
							</div>
							@endif
						</div>

					</div>
				</div>

			</div>
		</div>
	</div>
</div>
@endsection
