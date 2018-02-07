<link href="{{URL('public/assets/font-end/bootstrap.min.css')}}" rel="stylesheet">
<link href="{{URL('public/assets/font-end/styles.css')}}" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="{{URL('public/assets/js/bootstrap.min.js')}}"></script>
<div style="padding: 40px 0px; background: #f0f0f0;font-family: Arial, roboto; line-height: 23px; color: #666">
<div style="width:600px; margin: auto; background:#fff;">
<div style="background: #191a1c; padding: 20px; overflow: hidden; color: #fff;">
<div style="float: left;"><img height="50px" src="{{URL()}}/logo.jpg" /></div>
<div style="background:#191a1c;height: 140px;background-size: 100%; text-align: center; padding-top: 80px"></div>
<div style="padding: 30px">
<div style="font-size: 20px; margin-bottom: 20px; font-weight: bold;">Hi {{$firstname}} {{$lastname}}. Your channel {{$channel_name}} has been upload new video !</div>

<div class="row">
	
	<div class="col-sm-6 col-sm-6 image-left">
		<div class="col">
			<div class="col_img">
				<span class="hd">HD</span>
				<a href="{{URL('watch')}}/{{$video_id}}/{{str_slug($video_name)}}.html"><img src="{{$video_thumb}}" alt="{{$video_name}}" style="max-width: 100% !important" height="177"> </a><div class="position_text">
				<p class="icon-like"></p>
				<p class="percent">{{$rating}}%</p>
				<p class="time_minimute">{{$video_duration}}</p>
			</div>
		</div>
		<h3><a href="{{URL('watch')}}/{{$video_id}}/{{str_slug($video_name)}}.html">{{str_limit($video_name,30)}}</a></h3>
		</div>
	</div> 


</div>

<!-- <p><strong>Recommened for you</strong></p>
<br />
<div class="col-sm-6 col-sm-6 image-left">
		<div class="col">
			<div class="col_img">
				<a id="edit-thumbnail" data-toggle="tooltip" data-placement="right" title="Edit thumbnail" data-id="365492789" class="edit-thumbnail" href="javascript:void(0);"><span class="fa fa-picture-o"></span></a>
				<a id="edit-video" data-toggle="tooltip" data-placement="right" title="Delete video" class="edit-video" data-id="365492789" href="javascript:void(0);"><span class="fa fa-trash-o"></span></a>
				<span class="hd">HD</span>
				<a href="http://adult-streaming-website.localhost:70/watch/365492789/big-titted-construction-worker-shows-of-her-tits-and-plays-with-herself.html"><img src="http:\/\/i1.cdn2b.image.pornhub.phncdn.com\/m=eqgl9daaaa\/videos\/201508\/24\/55677101\/original\/6.jpg" alt="Big Titted Construction Worker shows of her tits and plays with herself" style="max-width: 100% !important" height="177"> </a><div class="position_text">
				<p class="icon-like"></p>
				<p class="percent">90%</p>
				<p class="time_minimute">05:33</p>
			</div>
		</div>
		<h3><a href="http://adult-streaming-website.localhost:70/watch/365492789/big-titted-construction-worker-shows-of-her-tits-and-plays-with-herself.html">Big Titted Construct...</a></h3>
		</div>
	</div> 
<br /> -->
 </div>
</div>
</div>