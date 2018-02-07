<div id="data-channel">
@if(isset($getchannel))
	@if(count($getchannel)>0)
	<h3></h3>
	<div class="page-channel">
			<div class="channel-bg">
				<div class="row main-channel">
					<div class="clearfix" style="height: 5px;"></div>
				@foreach($getchannel as $result)
					<div class="col-xs-6 col-sm-3 col-md-3" id="sub-channel-{{$result->id}}">
						<div class="channel-col">
							<span onclick="removeChannelSub('{{$result->id}}')"><i class="fa fa-rss"></i></span>
							<img src="{{$result->getImageUrl($result->poster)}}" class="img-responsive" alt="{{$result->title_name}}" />
							<h4><a href="{{URL(getLang().'channel')}}/{{$result->id}}/{{$result->post_name}}">{{$result->title_name}}</a><span></span></h4>
						</div>
					</div>
				@endforeach
				</div>
			</div>
		<div id="member-subscribe"  class="page_navigation">
			{!!$getchannel->render()!!}
			 <!--  -->
		</div>
	</div>
	@else
	<div class="">{{trans('home.NO_CHANNEL_FOR_SUBSCRIED')}}</div>
	@endif
@endif
</div>
<script type="text/javascript">
	function removeChannelSub(channelId) {
		console.log(channelId,'channelId');
		$.ajax({
            type: "GET",
            url: base_url + 'channel/' + channelId + "/remove-channel-sub.html",
            success: function(data) {
                if(data.status == 200) {
                    $("#sub-channel-" + channelId).remove();
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