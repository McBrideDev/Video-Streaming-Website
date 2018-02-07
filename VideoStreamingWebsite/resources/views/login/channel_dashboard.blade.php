<div id="msgloading"></div>
<div class="member-profile">
	<h2>{{trans('home.DASHBOARD')}}</h2>
		<div class="col-md-12" style="background: #2c2d2f">
        <div class="row image-left">
          <div class="col-md-3">        
                        <div class="margin-t10">
                            <div class="channel-total">
                                <span class="pull-left">{{trans('home.VIDEOS')}}</span>
                                <span class="pull-right">{{$total_video}}</span>
                            </div>
                            <div class="clearfix"></div>
                            <div class="channel-total">
                                <span class="pull-left">{{trans('home.VIEWS')}}</span>
                                <span class="pull-right">{{$channel_user->total_view}}</span>
                            </div>
                            <div class="clearfix"></div>
                            <div class="channel-total">
                                <span class="pull-left">{{trans('home.SUBSCRIBERS')}}</span>
                                <span class="pull-right"><?php echo count($total_subscriber)?></span>
                            </div>
                        </div>
          </div>
          <div class="col-md-9">
            <div class="margin-t10">
                  <p class="channel-hd-description"><span>{{ $channel_user->title_name}}</span> {{ $channel_user->description}}</p>     
            </div>
                  
          </div>

        </div>
	  </div>
</div>