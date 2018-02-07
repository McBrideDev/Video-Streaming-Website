<?php
use App\Helper\VideoHelper;
?>
@if(isset($pornstar_video))
<div class="titile-cate">
    <h2>{{$pornstar_video}}</h2>
</div>
<div class="row content-image videos">
    @foreach($video as $result)
    <?php $rating = VideoHelper::getRating($result->string_Id);?>
    <div class="col-xs-6 col-sm-3 image-left">
        <div class="col">
            <div class="col_img">
                <span class="hd">HD</span>
                <a href="{{URL(getLang().'watch')}}/{{$result->string_Id."/".$result->post_name}}.html">
                    <?php if ($result->poster==NULL) { ?>
                    <img src="{{URL('public/assets/images/no-image.jpg')}}" alt="{{$result->title_name}}" height="145" />
                    <?php  }else{?>
                    <img data-preview ="{{$result->preview}}" data-src="{{$result->getImageUrl($result->poster)}}" class="js-videoThumbFlip img-responsive" data-digitsSuffix="{{$result->digitsSuffix}}" data-digitsPreffix="{{$result->digitsPreffix}}" data-from="{{$result->website}}" src="{{$result->poster}}" alt="{{$result->title_name}}" />
                    <?php } ?>
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
   {!!$video->render()!!}
</div>
@endif
@if(isset($pornstar_photo))
<div class="titile-cate">
    <h2>{{$pornstar_photo}}</h2>
</div>
<div class="row content-image">
        <div class="porn-gallery" id="links col-md-12">
        @foreach($photo as $result)
                <a href="{{URL('public/upload/pornstar')}}/{{$result->photo}}" data-gallery>
                    <img style="margin-bottom:5px;" src="{{URL('public/upload/pornstar')}}/{{$result->photo}}" height="250" >
                </a>
        @endforeach
        </div>
</div>
<div class="page_navigation">
    {!!$photo->render()!!}
</div>
<!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
<?php if(count($pornstar_photo)>0){ ?>
<div id="blueimp-gallery" class="blueimp-gallery">
    <!-- The container for the modal slides -->
    <div class="slides"></div>
    <!-- Controls for the borderless lightbox -->
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
    <!-- The modal dialog, which will be used to wrap the lightbox content -->
    <div class="modal fade">
        <div class="modal-dialog" style="width:1000px !important" >
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" style="color:#fff !important; opacity: 1 !important" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body next"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left prev">
                        <i class="glyphicon glyphicon-chevron-left"></i>
                        {{trans('home.PREVIOUS')}}
                    </button>
                    <button type="button" class="btn btn-primary next">
                        {{trans('home.NEXT')}}
                        <i class="glyphicon glyphicon-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }?>
@endif
