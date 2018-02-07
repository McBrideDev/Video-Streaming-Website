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
        @else
        @endif
        <div class="titile-cate">
            <?= get_title_datetime(); ?>
            <ul>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">{{trans('home.VIEWS')}}<span class="caret"></span></a>
                    <ul class="dropdown-menu" id="video-filter">
                        <li class="active"><a href="javascript:void(0)" data-value="views">{{trans('home.VIEWS')}}</a></li>
                        <li><a href="javascript:void(0)" data-value="rating-video">{{trans('home.RATING')}}</a></li>
                        <li><a href="javascript:void(0)" data-value="duration">{{trans('home.DURATIONS')}}</a></li>
                        <li><a href="javascript:void(0)" data-value="date">{{trans('home.DATE')}}</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="row content-image videos">
            <?php $item = 1; ?>
            @foreach($views as $result)
            <?php $rating = VideoHelper::getRating($result->string_Id); ?>
            <div class="col-xs-6 col-sm-3 col-md-2 image-left">
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
            <?php if ($item == 3) { ?>
              <div class="hidden-xs hidden-sm col-xs-6 col-sm-4 col-md-4 image-right pull-right">
                  <div class="ads-here-right">
                      <p class="advertisement">{{trans('home.ADVERTISEMENT')}}</p>
                      <?php echo StandardAdHome(); ?>
                  </div>
              </div>
            <?php } ?>
            <?php if ($item == 8) { ?><div class="clearfix"></div><?php } ?>
            <?php $item++; ?>
            @endforeach
        </div>
        <div class="page_navigation">
            {!!$views->render()!!}
        </div>
    </div>
</div>


@endsection
