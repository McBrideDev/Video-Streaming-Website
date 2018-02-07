<?php
use App\Helper\VideoHelper;
?>
@extends('master-frontend')
@section('title', 'Search Result')
@section('content')
<div class="main-content">
    <div class="container-fluid top-rate ">
        <div class="row categories_page" >
            <input type="hidden" id="keyword" value="{{$keyword}}">
            <div class="col-md-4">
                <div class="view-cat-col titile-cate" style="border: none; font-size: 15px">
                    {{trans('home.SORT_BY_DATE')}}
                    <ul style="position: relative; top:-3px;width: 140px;">
                        <li id="close-sort" class="dropdown">
                            <a href="#"  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i id="txt-sort-by">{{trans('home.RELEVANCE')}}</i><span class="caret"></span></a>
                            <ul id="sort_by" class="dropdown-menu" style="width:140px; min-width: 140px;">
                                <li><a data-sort="relevance" full-text="{{trans('home.RELEVANCE')}}" class="active" href="javascript:void(0)">{{trans('home.RELEVANCE')}}</a></li>
                                <li><a data-sort="uploaddate" full-text="{{trans('home.UPLOAD')}}" href="#">{{trans('home.UPLOAD')}}</a></li>
                                <li><a data-sort="mostviewed" full-text="{{trans('home.MOST_VIEWED')}}" href="#">{{trans('home.MOST_VIEWED')}}</a></li>
                                <li><a data-sort="rating" full-text="{{trans('home.RATING')}}" href="#">{{trans('home.RATING')}}</a></li>
                            </ul>
                        </li>
                    </ul>
                    <input type="hidden" id="sort_by_default" value="relevance">
                </div>
            </div>
            <div class="col-md-4">
                <div class="view-cat-col titile-cate" style="border: none;font-size: 15px">
                    {{trans('home.DATE_ADDED')}}
                    <ul style="position: relative; top:-3px; width: 140px;">
                        <li id="close-date" class="dropdown">
                            <a href="#"  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i id="txt-date-sort">{{trans('home.ALL_TIMES')}}</i><span class="caret"></span></a>
                            <ul id="sort_date" class="dropdown-menu" style="width:140px; min-width: 140px;">
                                <li><a data-sort-date="all" full-text="{{trans('home.ALL_TIMES')}}" class="active" href="#">{{trans('home.ALL_TIMES')}}</a></li>
                                <li><a data-sort-date="today" full-text="{{trans('home.TO_DAY')}}" href="#">{{trans('home.TO_DAY')}}</a></li>
                                <li><a data-sort-date="week" full-text="{{trans('home.THIS_WEEK')}}" href="#">{{trans('home.THIS_WEEK')}}</a></li>
                                <li><a data-sort-date="month" full-text="{{trans('home.THIS_MONTH')}}" href="#">{{trans('home.THIS_MONTH')}}</a></li>
                            </ul>
                        </li>
                    </ul>
                    <input type="hidden" id="sort_date_default" value="all">
                </div>
            </div>
            <div class="col-md-4">
                <div class="view-cat-col  titile-cate" style="border: none;font-size: 15px">
                    {{trans('home.LENGTH_OF_VIDEO')}}
                    <ul class="hidden-xs" style="position: relative; top:-3px; width: 195px;">
                        <li id="close-duration" class="dropdown">
                            <a href="#"  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i id="txt-duration-sort">{{trans('home.ALL')}}</i><span class="caret"></span></a>
                            <ul id="sort_time" class="dropdown-menu" style="width:195px; min-width: 195px;">
                                <li><a data-sort-time="all" full-text="{{trans('home.ALL')}}" class="active" href="#">{{trans('home.ALL')}}</a></li>
                                <li><a data-sort-time="1-3" full-text="{{trans('home.SHORT_VIDEOS')}} (1-3min)" href="#">{{trans('home.SHORT_VIDEOS')}} (1-3{{trans('home.MIN')}})</a></li>
                                <li><a data-sort-time="3-10" full-text="{{trans('home.MEDIUM_VIDEOS')}} (3-10min)" href="#">{{trans('home.MEDIUM_VIDEOS')}} (3-10{{trans('home.MIN')}})</a></li>
                                <li><a data-sort-time="10+" full-text="{{trans('home.LONG_VIDEOS')}} (+10min)" href="#">{{trans('home.LONG_VIDEOS')}} (+10{{trans('home.MIN')}})</a></li>

                            </ul>
                        </li>
                    </ul>
                    <ul class="visible-xs" style="position: relative; top:-3px; width: 140px;">
                        <li id="close-duration" class="dropdown">
                            <a href="#"  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i id="txt-duration-sort">All</i><span class="caret"></span></a>
                            <ul id="sort_time" class="dropdown-menu" style="width:140px; min-width: 140px;">
                                <li><a data-sort-time="all" full-text="All" class="active" href="#">All</a></li>
                                <li><a data-sort-time="1-3" full-text="Short (1-3min)" href="#">Short (1-3min)</a></li>
                                <li><a data-sort-time="3-10" full-text="Medium (3-10min)" href="#">Medium (3-10min)</a></li>
                                <li><a data-sort-time="10+" full-text="Long (+10min)" href="#">Long (+10min)</a></li>
                                <!-- end xs -->
                            </ul>
                        </li>
                    </ul>
                    <input type="hidden" id="sort_time_default" value="all">
                </div>
            </div>
        </div>
        <div class="titile-cate" style="margin-bottom: 0">
            <h2>"{{$keyword}}" {{trans('home.SEARCH')}} - <i id="c-result">{{count($video)}}</i> {{trans('home.RESULTS')}}</h2>
        </div>
        <div id="ajax-result-content">
            <div class="row content-image videos">

                @foreach($video as $result)
                <?php $rating = VideoHelper::getRating($result->string_Id); ?>
                <div class="col-xs-6 col-sm-4 col-md-2 image-left">
                    <div class="col">
                        <div class="col_img">
                            <span class="hd">HD</span>
                            <a href="{{URL(getLang().'watch')}}/{{$result->string_Id."/".$result->post_name}}.html">
                                <img data-preview ="{{$result->preview}}" data-src="{{$result->getImageUrl($result->poster)}}" class="js-videoThumbFlip img-responsive" data-digitsSuffix="{{$result->digitsSuffix}}" data-digitsPreffix="{{$result->digitsPreffix}}" data-from="{{$result->website}}" src="{{$result->getImageUrl($result->poster)}}" alt="{{$result->title_name}}" />
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
                @endforeach
            </div>
            <div  class="page_navigation">
                {!!$video->render()!!}
            </div>
        </div>
    </div>
</div>
@endsection
