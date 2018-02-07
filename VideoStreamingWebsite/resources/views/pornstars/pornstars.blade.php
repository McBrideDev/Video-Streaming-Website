<?php

use App\Helper\PornStarHelper;

?>
@extends('master-frontend')
@section('title', 'Porn Stars')
@section('content')
<div class="main-content pornstars_page">
    <div class="container-fluid pornstars_page">
        <div class="titile-cate">
            <h1>{{trans('home.PORNSTAR')}}</h1>
            <div align="centers" class="rate-filter">
                <span>{{trans('home.BROWSE_BY_NAME')}}:</span>
                <ul id="pornstars-fillter">
                    <li><a data="specialKey" href="javascript:void(0);">#</a></li>
                    <li><a data="A" href="javascript:void(0);">A</a></li>
                    <li><a data="B" href="javascript:void(0);">B</a></li>
                    <li><a data="C" href="javascript:void(0);">C</a></li>
                    <li><a data="D" href="javascript:void(0);">D</a></li>
                    <li><a data="E" href="javascript:void(0);">E</a></li>
                    <li><a data="F" href="javascript:void(0);">F</a></li>
                    <li><a data="G" href="javascript:void(0);">G</a></li>
                    <li><a data="H" href="javascript:void(0);">H</a></li>
                    <li><a data="I" href="javascript:void(0);">I</a></li>
                    <li><a data="J" href="javascript:void(0);">J</a></li>
                    <li><a data="K" href="javascript:void(0);">K</a></li>
                    <li><a data="L" href="javascript:void(0);">L</a></li>
                    <li><a data="M" href="javascript:void(0);">M</a></li>
                    <li><a data="N" href="javascript:void(0);">N</a></li>
                    <li><a data="O" href="javascript:void(0);">O</a></li>
                    <li><a data="P" href="javascript:void(0);">P</a></li>
                    <li><a data="Q" href="javascript:void(0);">Q</a></li>
                    <li><a data="R" href="javascript:void(0);">R</a></li>
                    <li><a data="S" href="javascript:void(0);">S</a></li>
                    <li><a data="T" href="javascript:void(0);">T</a></li>
                    <li><a data="U" href="javascript:void(0);">U</a></li>
                    <li><a data="V" href="javascript:void(0);">V</a></li>
                    <li><a data="W" href="javascript:void(0);">W</a></li>
                    <li><a data="X" href="javascript:void(0);">X</a></li>
                    <li><a data="Y" href="javascript:void(0);">Y</a></li>
                    <li><a data="Z" href="javascript:void(0);">Z</a></li>
                    <li><a data="all" class="letter" onclick="javascript:window.location.reload()" href="javascript:void(0);">{{trans('home.ALL')}}</a></li>
                </ul>
            </div>
        </div>
        <div class="row">

            <div id="result-filter-porn-star">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
                    <div class="row content-image">
                        <?php $items = 1; ?>
                        @foreach($pornstar as $key => $result)
                        <div class="col-md-2 col-sm-3 col-xs-6 image-left">
                            <div class="col">
                                <div class="col_img">
                                    <a class="text-capitalize" href="{{URL(getLang().'pornstars')}}/{{$result->id}}/{{$result->post_name}}">
                                    <img class="img-responsive pornstar_img on-error-img" src="{{URL('/') . '/public/upload/pornstar/' . $result->poster }}" alt="{{$result->title_name}}" /></a>
                                </div>
                                <h3 class="pornstar-title">
                                    <a class="text-capitalize" href="{{URL(getLang().'pornstars')}}/{{$result->id}}/{{$result->post_name}}">{{$result->title_name}}</a>
                                    <span>{{$result->video_numbers}}</span>
                                </h3>
                            </div>
                        </div>
                        <?php if ($items == 2) { ?>
                          <div class="col-sm-6 col-md-4 col-xs-6 image-right pull-right hidden-xs hidden-sm">
                              <div class="ads-here-right porn-ads" >
                                  <p class="advertisement">{{trans('home.ADVERTISEMENT')}}</p>
                                  <?= StandardAdPornstar(); ?>
                              </div>
                          </div>
                          <div class="clearfix visible-xs"></div>
                          <div class="col-sm-6 image-left visible-xs">
                              <div class="ads-here-right">
                                  <p class="advertisement">{{trans('home.ADVERTISEMENT')}}</p>
                                  <?= StandardAdPornstar(); ?>
                              </div>
                          </div>
                          <div class="clearfix visible-xs"></div>
                        <?php } ?>
                        <?php if ($items == 8) { ?><div class="clearfix hidden-xs hidden-sm" style="margin-bottom: 15px"></div><?php } ?>
                        <?php $items++; ?>
                        @endforeach

                    </div>
                </div>
                <div class="page_navigation">
                    {!!$pornstar->render()!!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
