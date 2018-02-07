<?php
use App\Helper\VideoHelper;
?>
<div class="row videos content-image">
    @if(count($video)>0)
    @foreach($video as $result)
    <?php $rating = VideoHelper::getRating($result->string_Id);?>
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
            <span class="titleviews">{{$result->total_view ===NULL ? 0: $result->total_view}} {{trans('home.VIEWS')}}  <span class="titlerating"><i class="fa fa-thumbs-up" aria-hidden="true"></i> {{floor($rating['percent_like'])}}%</span></span>
        </div>
    </div>
    @endforeach
    @else
    {{trans('home.VIDEO_NOT_FOUND')}}. {{trans('home.UPLOAD')}} <a class="click-here" href="{{URL(getLang().'member-proflie.html?action=upload')}}">{{trans('home.CLICK_HERE')}}</a>
    @endif
</div>
<!-- PAGE -->
<div id="result-video-filter-paginate" class="page_navigation">
    {!!$video->render()!!}
</div>
