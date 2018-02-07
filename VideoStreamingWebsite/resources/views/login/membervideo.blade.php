<?php
use App\Helper\VideoHelper;
?>
<div id="data">
@if(isset($video))
<h3>
	@if(isset($msgmember))
	{{$msgmember}}
	@else
	<!-- {{count($video)}} Video In Your Favorite -->
	@endif
</h3>

<div  class="row image-left videos">
   @foreach ($video as $result)
   <?php $rating = VideoHelper::getRating($result->string_Id);?>
     <div class="col-xs-6 col-sm-3 col-md-2 box1" id="video-fav-{{$result->string_Id}}">
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
            <span class="titleviews">
                {{$result->total_view ===NULL ? 0: $result->total_view}} {{trans('home.VIEWS')}}
                <span class="titlerating">
                    <i class="fa fa-thumbs-up" aria-hidden="true"></i> {{floor($rating['percent_like'])}}%
                </span>

                <span class="titlerating">
                    {{-- <a href="{{url('videos/'.$result->string_Id.'/remove-video-fav.html')}}"> --}}
                    {{-- <a href="#" id="remove-video-fav"> --}}
                        <i id="remove-video-fav" title="remove" onclick="removeVideoFav('{{$result->string_Id}}')" class="glyphicon glyphicon-heart pink" style="color: #ee577c; cursor: pointer;" aria-hidden="true"></i>
                    {{-- </a> --}}
                </span>
            </span>
        </div>
    </div>
     @endforeach
     <div class="clearfix"></div>
     <div id="page_navigation" class="page_navigation">
         {!!$video->render()!!}
    </div>
</div>
@endif
</div>
<script>
    function removeVideoFav(id) {
        // console.log(id, 'id');
        $.ajax({
            type: "GET",
            url: base_url + 'videos/' + id + "/remove-video-fav.html",
            success: function(data) {
                if(data.status == 200) {
                    $("#video-fav-" + id).remove();
                }
            },
            beforeSend: function() {
                $('#result-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").show();
            },
            complete: function() {
                $('#result-load').html("<img src='" + base_asset + "/public/assets/images/result_loading.gif'/>").hide();
            }
        });
    }
</script>
