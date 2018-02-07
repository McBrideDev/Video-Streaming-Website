<?php
use App\Helper\VideoHelper;
?>
<div id="member-data-video">


	@if(isset($msgmember))
	<h3>{{$msgmember}}</h3>
	@else
	<h3>{{trans('home.VIDEO_COLLECTIONS')}}</h3>
	@endif

@if(isset($video))
<div  class="row image-left videos">
   @foreach ($video as $result)
   <?php $rating = VideoHelper::getRating($result->string_Id);?>
     <div class="col-md-4 box1">
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
                <span class="titleviews">{{$result->total_view ===NULL ? 0: $result->total_view}} {{trans('home.VIEWS')}}  <span class="titlerating"><i class="fa fa-thumbs-up" aria-hidden="true"></i> {{floor($rating['percent_like'])}}%</span></span>
            </div>
        </div>
     @endforeach
     <div class="clearfix"></div>
     <input type="hidden" member-id="{{$memberid}}" id="memberIdProfile" value="">
     <div id="page-navigation-member-profile" class="page_navigation">
         {!!$video->render()!!}
    </div>
</div>
@endif
</div>
