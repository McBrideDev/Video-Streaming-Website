<?php
use App\Helper\VideoHelper;
?>
@extends('master-frontend')
@section('title', 'Porn Star')
@section('content')
<div class="main-content">
    <div id="profile" class="pornStar-profile">
        <div id="head">
            <div class="wp pornstar-banner hidden-xs">
                <img src="{{$check_wall}}" class="on-error-banner">
            </div>
            <!-- md -->
            <div class="profileLeft contents hidden-xs">
                <div class="pp">
                    <img src="{{$check_thumb}}" class="img-responsive" alt="{{$pornstar->title_name}}">
                </div>
                <h2><a href="#" style="color: white;">{{$pornstar->title_name}}</a></h2>
                <p>{{number_format($total_video)}} {{trans('home.VIDEOS')}}</p>
                <p class="followers"></p>
            </div>
            <!-- end-md -->
            <!-- xs -->
            <div class="profileLeft contents visible-xs profileLeft-xs">
                <div class="pp">
                    <img src="{{$check_thumb}}" class="img-responsive" alt="{{$pornstar->title_name}}">
                </div>
                <h2><a href="#">{{$pornstar->title_name}}</a></h2>
                <p>{{number_format($total_video)}} {{trans('home.VIDEOS')}}</p>
                <p class="followers"></p>
            </div>
            <!-- end-xs -->
        </div>
        <div class="textBio"><a id="show-about" href="#about"><i class="fa fa-chevron-down"></i></a>
            <p>{{$pornstar->description}}</p>
            <div class="overlay"></div>
        </div>

        <div class="infoBar">
            <div class="userNav">
                <!-- md -->
                <div class="profileLeft hidden-xs">
                    <div id="msg-rating" class="rate">
                        <a href="javascript:void(0);" id="pornvote-like-{{$pornstar->id}}"  class="tt vUp" title="Like"><i class="fa fa-thumbs-o-up"></i><span>{{$pornstar_like}}</span></a>
                        <a href="javascript:void(0);" id="pornvote-dislike-{{$pornstar->id}}" class="tt vDn" title="Dislike"><i class="fa fa-thumbs-o-down"></i><span>{{$pornstar_dislike}}</span></a>
                        <span class="result">
                            <span  style="width:{{$percent_rating['percent_like']}}%;"></span>
                        </span>
                    </div>
                </div>
                <!-- end-md -->
                <!--xs -->
                <div class="profileLeft visible-xs profileLeft-xs">
                    <div id="msg-rating" class="rate">
                        <a href="javascript:void(0);" id="pornvote-like-{{$pornstar->id}}" class="tt vUp"  title="Like"><i class="fa fa-thumbs-o-up"></i><span>{{$pornstar_like}}</span></a>
                        <a href="javascript:void(0);" id="pornvote-dislike-{{$pornstar->id}}" class="tt vDn"  title="Dislike"><i class="fa fa-thumbs-o-down"></i><span>{{$pornstar_dislike}}</span></a>
                        <span class="result">
                            <span style="width:{{$percent_rating['percent_like']}}%;"></span>
                        </span>
                    </div>
                </div>
                <!-- end-xs -->
                <!--md-->
                <a href="javascript:void(0)" data-href="{{URL(getLang().'pornstars')}}/{{$pornstar->id}}/{{$pornstar->post_name}}/video" id="pornmenu-video-{{$pornstar->ID}}" class=" hidden-xs active">{{trans('home.VIDEOS')}} <span><?= number_format($total_video) ?></span></a>
                <a href="javascript:void(0)" data-href="{{URL(getLang().'pornstars')}}/{{$pornstar->id}}/{{$pornstar->post_name}}/photo" id="pornmenu-photo-{{$pornstar->ID}}" class="hidden-xs">{{trans('home.PHOTOS')}}<span><?= number_format($total_photo); ?></span></a>
                <!-- end-md -->
                <!-- xs -->
                <a href="javascript:void(0)" data-href="{{URL(getLang().'pornstars')}}/{{$pornstar->id}}/{{$pornstar->post_name}}/video" id="pornmenu-video-{{$pornstar->ID}}" class="visible-xs userNav-a-xs active"><i class="fa fa-video-camera"></i><span>{{number_format($total_video)}}</span></a>
                <a href="javascript:void(0)" data-href="{{URL(getLang().'pornstars')}}/{{$pornstar->id}}/{{$pornstar->post_name}}/photo" id="pornmenu-photo-{{$pornstar->ID}}" class="visible-xs userNav-a-xs active" ><i class="fa fa-camera"></i><span><?= number_format($total_photo); ?></span></a>
                <!-- end-xs -->
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div style="padding: 0 5px">
        <div id="pornstar-content-profile" style="background: #282a2c" class="container-fluid top-rate ">
            <div class="row">
                <div class="col-md-3" style="margin-top: 15px">
                    <div class="profileLeft">
                        <div class="bio">
                            <p><i class="fa fa-play"></i><span>{{trans('home.TOTAL_VIDEOS')}}:</span> <?= $total_video; ?></p>
                            <p><i class="fa fa-thumbs-up"></i><span>{{trans('home.RATING')}}:</span> <?= $percent_rating['percent_like']; ?>% (<?= number_format($total_votes); ?> Votes)</p>
                            <p><i class="fa fa-line-chart"></i><span>Video Views:</span> <?= number_format($sum_view); ?></p>
                            <p><i class="fa fa-eye"></i><span>{{trans('home.AVERAGE_VIEWS')}}:</span> <?= ($total_video > 0) ? number_format($sum_view / $total_video) : 0 ?></p>
                            <p><i class="fa fa-venus-mars"></i></i><span>{{trans('home.GENDER')}}:</span> <?= ($pornstar->gender == 1) ? trans('home.FEMALE') : trans('home.MALE'); ?></p>
                            <hr><p><i class="fa fa-globe"></i><span>{{trans('home.ETHNICITY')}}:</span> <?= ($pornstar->ethnicity != NULL) ? $pornstar->ethnicity : trans('home.UPDATE'); ?></p>
                            <p><i class="fa fa-adjust"></i><span>{{trans('home.HAIR_COLOR')}}:</span> <?= ($pornstar->hair_color != NULL) ? $pornstar->hair_color : trans('home.UPDATE'); ?></p>
                            <p style="margin-bottom: 15px;"><i class="fa fa-eye"></i><span>{{trans('home.EYE_COLOR')}}:</span> <?= ($pornstar->eye_color != NULL) ? $pornstar->eye_color : trans('home.UPDATE'); ?> </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 main-pornstar" style="margin-top: 15px">
                    <div id="menu_result">
                        <div class="titile-cate">
                            <h2>{{$pornstar->title_name}}'s {{trans('home.VIDEOS')}}</h2>
                        </div>
                        <div class="row content-image videos">
                            @foreach($pornstar_video as $result)
                            <?php $rating = VideoHelper::getRating($result->string_Id); ?>
                            <div class="col-xs-6 col-sm-4 col-md-3 image-left">
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
                            @endforeach
                        </div>
                        <div class="page_navigation">
                            {!!$pornstar_video->render()!!}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
