<?php
use App\Helper\AppHelper;
?>
<div class="row content-image videos videoRelate hidden-xs hidden-sm">
    <div class="col-sm-6 col-sm-offset-3">
    @foreach($related as $result)
    <?php $rating = App\Helper\VideoHelper::getRating($result->string_Id);?>

    <div class="col-xs-6 col-sm-4 col-md-6 image-left">
        <div class="col">
            <div class="col_img">
                <span class="hd">HD</span>
                <a href="{{URL(getLang().'watch')}}/{{$result->string_Id."/".$result->post_name}}.html">
                    <img data-preview ="{{$result->preview}}" data-src="{{$result->getImageUrl($result->poster)}}" class="js-videoThumbFlip img-responsive" data-digitsSuffix="{{$result->digitsSuffix}}" data-digitsPreffix="{{$result->digitsPreffix}}" data-from="{{$result->website}}" src="{{$result->getImageUrl($result->poster)}}" alt="{{$result->title_name}}"/>
                </a>
                <div class="position_text">
                    <p class="icon-like"></p>
                    <p class="percent">{{floor($rating['percent_like'])}}%</p>
                    <p class="time_minimute">{{sec2hms($result->duration)}}</p>
                </div>
            </div>
            <h3><a href="{{URL(getLang().'watch')}}/{{$result->string_Id."/".$result->post_name}}.html">{{str_limit($result->title_name,20)}}</a></h3>
        </div>
    </div>
    @endforeach
    </div>
</div>
<style type="text/css" media="screen">
 .videoRelate .image-left {
    padding-left: 0px !important;
    padding-right: 1px !important;
    padding-bottom: 1px !important;
  }
  .videoRelate{
    z-index: 5;
    color: #fff;
    top: 7%;
    position: absolute;
    height: 100%;
    margin-bottom: 10px;
    right: 2%;
    width: 100%;
 }
 .codo-player-controls-wrap{
  z-index: 9999
 }
 .videoRelate .col_img img{
  width: 100%;
  height: 155px !important;
 }
 .videoRelate .box-relate{
  width: 25%;
  margin-bottom: 1px;
  float: left;
 }
 .videoRelate .box-relate:hover{
  /*opacity: 0.7;*/
 }
.videoRelate .box-relate .related_title{
    color: #e39000;
    word-break: break-word;
    position:  absolute;
    left:5;
}
</style>
