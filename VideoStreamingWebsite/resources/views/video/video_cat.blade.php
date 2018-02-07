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
                <h2>{{trans('home.VIDEO_IN')}} {{$catname}}</h2>
                <div class="row videos content-image">
                    @if(count($video_cat)>0)
                    @foreach($video_cat as $result)
                    <?php $rating = VideoHelper::getRating($result->string_Id); ?>
                    <div class="col-xs-6 col-sm-4 col-md-3 image-left">
                        <div class="col">
                            <div class="col_img text-center">
                                <span class="hd">HD</span>
                                <a href="{{URL(getLang().'watch')}}/{{$result->string_Id."/".$result->post_name}}.html">
                                    <img data-preview ="{{$result->preview}}" data-src="{{$result->poster}}" class="js-videoThumbFlip img-responsive on-error-image" data-digitsSuffix="{{$result->digitsSuffix}}" data-digitsPreffix="{{$result->digitsPreffix}}" data-from="{{$result->website}}" src="{{$result->poster}}" alt="{{$result->title_name}}" />
                                    <div class="position_text">
                                        <p class="time_minimute">{{sec2hms($result->duration)}}</p>
                                    </div>
                            </div>
                            <h3>
                                <a href="{{URL(getLang().'watch')}}/{{$result->string_Id."/".$result->post_name}}.html">{{$result->title_name}}</a>
                            </h3>
                            <span class="titleviews">{{$result->total_view ===NULL ? 0: $result->total_view}} {{trans('home.VIEWS')}}  <span class="titlerating"><i class="fa fa-thumbs-up" aria-hidden="true"></i> {{floor($rating['percent_like'])}}%</span></span>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="col-xs-12"> {{trans('home.VIDEO_NOT_FOUND_FORM_CATEGORIES')}} </div>
                    @endif
                </div>
                <!-- PAGE -->
                <div class="page_navigation">
                    {!!$video_cat->render()!!}
                </div>
                <!-- END PAGE -->
            </div>
        </div>
    </div>
</div>
@endsection
