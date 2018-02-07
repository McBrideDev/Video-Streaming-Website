<?php
use App\Helper\VideoHelper;
use App\Helper\AppHelper;

$categories = AppHelper::getCategoryList();
?>
@extends('master-frontend')
@section('title', 'Video')
@section('content')
<div class="main-content categories_page">
    <div class="container-fluid pad-l-r-50">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3 view_cat">
                <div class="view-cat-col">
                    <h2><center>{{trans('home.CATEGORY')}}</center></h2>
                    <ul>
                        @foreach($categories as $result)
                        <li><a href="{{URL(getLang().'video.html&action=cat&catid=')}}{{$result->id}}">{{$result->title_name}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-xs-12 col-sm-8 col-md-9 right_content image-left pad-l-r-videoBox">
                <div class="titile-cate">
                    <span class="hidden-xs">{{$title}}</span>
                    <span class="visible-xs">{{$title_xs}}</span>
                    <!-- duration -->
                    <ul class="hidden-xs" style="width: 200px;">
                        <li class="dropdown">
                            <a href="#" id="set-time-video" data-action="{{$action}}"  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true" data-time="all" ><i id="txt-time-video">{{trans('home.ALL_DURATION')}}</i><span class="caret"></span></a>
                            <ul id="chose-time-video" data-action="{{$action}}" class="dropdown-menu">
                                <li class="time hidden-xs" ><a role="all" full-text="{{trans('home.ALL_DURATION')}}" href="javascript:void(0);">{{trans('home.ALL_DURATION')}}</a></li>
                                <li class="time hidden-xs" ><a role="1-3" full-text="{{trans('home.SHORT_VIDEOS')}} (1-3{{trans('home.MIN')}})" href="javascript:void(0);">{{trans('home.SHORT_VIDEOS')}} (1-3{{trans('home.MIN')}})</a></li>
                                <li class="time hidden-xs" ><a role="3-10" full-text="{{trans('home.MEDIUM_VIDEOS')}} (3-10{{trans('home.MIN')}})" href="javascript:void(0);">{{trans('home.MEDIUM_VIDEOS')}} (3-10{{trans('home.MIN')}})</a></li>
                                <li class="time hidden-xs" ><a role="10+" full-text="{{trans('home.LONG_VIDEOS')}} (+10{{trans('home.MIN')}})" href="javascript:void(0);">{{trans('home.LONG_VIDEOS')}} (+10{{trans('home.MIN')}})</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="visible-xs" style="; position: relative; top:-3px;">
                        <li class="dropdown">
                            <a href="#" id="set-time-video" data-action="{{$action}}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true" data-time="all" ><i id="txt-time-video">{{trans('home.ALL_DURATION')}}</i><span class="caret"></span></a>
                            <ul id="chose-time-video" data-action="{{$action}}" class="dropdown-menu" style="min-width:110px !important; width:110px ">
                                <li class="time visible-xs" ><a role="all" full-text="{{trans('home.ALL')}}" href="javascript:void(0);">{{trans('home.ALL_DURATION')}}</a></li>
                                <li class="time visible-xs" ><a role="1-3" full-text="{{trans('home.SHORT_VIDEOS')}} (1-3{{trans('home.MIN')}})" href="javascript:void(0);">{{trans('home.SHORT_VIDEOS')}} (1-3{{trans('home.MIN')}})</a></li>
                                <li class="time visible-xs" ><a role="3-10" full-text="{{trans('home.MEDIUM_VIDEOS')}} (3-10{{trans('home.MIN')}})" href="javascript:void(0);">{{trans('home.MEDIUM_VIDEOS')}} (3-10{{trans('home.MIN')}})</a></li>
                                <li class="time visible-xs" ><a role="10+" full-text="{{trans('home.LONG_VIDEOS')}} (+10{{trans('home.MIN')}})" href="javascript:void(0);">{{trans('home.LONG_VIDEOS')}} (+10{{trans('home.MIN')}})</a></li>
                            </ul>
                        </li>
                    </ul>
                    <!--end duration -->
                    @if($action=="new")
                    <input id="date-sort-video" data-date="all" type="hidden" name="" value="">
                    @else
                    <!--Date -->
                    <ul class="hidden-xs">
                        <li class="dropdown">
                            <a href="#" id="date-sort-video" data-action="{{$action}}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true" data-date="all" ><i id="txt-date-video">{{trans('home.ALL_TIMES')}}</i><span class="caret"></span></a>
                            <ul id="chose-date-video" data-action="{{$action}}" class="dropdown-menu">
                                <li class="date-sort hidden-xs" ><a role="all" full-text="{{trans('home.ALL_TIMES')}}" href="javascript:void(0);">{{trans('home.ALL_TIMES')}}</a></li>
                                <li class="date-sort hidden-xs" ><a role="today" full-text="{{trans('home.TO_DAY')}}" href="javascript:void(0);">{{trans('home.TO_DAY')}}</a></li>
                                <li class="date-sort hidden-xs" ><a role="week" full-text="{{trans('home.THIS_WEEK')}}" href="javascript:void(0);">{{trans('home.THIS_WEEK')}}</a></li>
                                <li class="date-sort hidden-xs" ><a role="month" full-text="{{trans('home.THIS_MONTH')}}" href="javascript:void(0);">{{trans('home.THIS_MONTH')}}</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="visible-xs"  style="max-width:100px !important;width:70px !important; position: relative; top:-3px;">
                        <li class="dropdown">
                            <a href="#" id="date-sort-video" data-action="{{$action}}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true" data-date="all" ><i id="txt-date-video">{{trans('home.ALL_TIMES')}}</i><span class="caret"></span></a>
                            <ul id="chose-date-video" data-action="{{$action}}" class="dropdown-menu" style="min-width:100px !important; width:100px !important ">
                                <li class="date-sort visible-xs" ><a role="all" full-text="{{trans('home.ALL_TIMES')}}" href="javascript:void(0);">{{trans('home.ALL_TIMES')}}</a></li>
                                <li class="date-sort visible-xs" ><a role="today" full-text="{{trans('home.TO_DAY')}}" href="javascript:void(0);">{{trans('home.TO_DAY')}}</a></li>
                                <li class="date-sort visible-xs" ><a role="week" full-text="{{trans('home.THIS_WEEK')}}" href="javascript:void(0);">{{trans('home.THIS_WEEK')}}</a></li>
                                <li class="date-sort visible-xs" ><a role="month" full-text="{{trans('home.THIS_MONTH')}}" href="javascript:void(0);">{{trans('home.THIS_MONTH')}}</a></li>
                            </ul>
                        </li>
                    </ul>
                    <!-- endDate -->
                    @endif
                </div>

                <div id="result-video-filter-page">
                    <div class="row videos content-image">
                        @if(count($video) > 0)
                        @foreach($video as $result)
                        <?php $rating = VideoHelper::getRating($result->string_Id); ?>
                        <div class="col-xs-6 col-sm-4 col-md-3 image-left">
                            <div class="col">
                                <div class="col_img">
                                    <span class="hd">HD</span>
                                    <a href="{{URL(getLang().'watch')}}/{{$result->string_Id."/".$result->post_name}}.html">
                                        <img data-preview ="{{$result->preview}}" data-src="{{$result->poster}}" class="js-videoThumbFlip img-responsive on-error-img" data-digitsSuffix="{{$result->digitsSuffix}}" data-digitsPreffix="{{$result->digitsPreffix}}" data-from="{{$result->website}}" src="{{$result->poster}}" alt="{{$result->title_name}}" />
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
                        @else
                        <div class="col-xs-12 col-sm-6 col-md-3">{{trans('home.VIDEO_NOT_FOUND')}}</div>
                        @endif
                    </div>
                    <!-- PAGE -->
                    <div  class="page_navigation">
                        {!!$video->render()!!}
                    </div>
                </div>
                <!-- END PAGE -->
            </div>
        </div>
    </div>
</div>
@endsection
