<?php
use App\Helper\AppHelper;
?>
<div class="row content-image videos">
    @if(count($video)>0)
        @foreach($video as $result)
        <?php $rating = App\Helper\VideoHelper::getRating($result->string_Id);?>
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
    @else
        {{trans('home.VIDEO_NOT_FOUND')}}
    @endif
</div>
<div id="search-filter-page" class="page_navigation">
    {!!$video->render()!!}
</div>
<script type="text/javascript">
    $(window).load(function(){
        $('#c-result').empty().text('{{$count_video}}')
        console.log($('#c-result').text())
    })
</script>
