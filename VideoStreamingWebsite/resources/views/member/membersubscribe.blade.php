<div id="data-channel">
@if(isset($getchannel))

<h3>{{trans('home.CHANNEL_SUBSCRIBER')}}</h3>
<div class="page-channel">
        <div class="channel-bg">

            <div class="row main-channel">
                <div class="clearfix" style="height: 5px;"></div>
            @foreach($getchannel as $result)
                <div class="col-xs-6 col-sm-6 col-md-3">
                    <div class="channel-col">
                        <img src="{{$result->getImageUrl($result->poster)}}" class="img-responsive" width="277" height="111" alt="{{$result->title_name}}" />
                        <h4><a target="_blank" href="{{URL('channel')}}/{{$result->id}}/{{$result->post_name}}">{{$result->title_name}}</a><span></span></h4>
                    </div>
                </div>
            @endforeach
            </div>
        </div>

    <div id="member-subscribe-page-navigation" member-id="{{$memberid}}" class="page_navigation">
        {!!$getchannel->render()!!}

    </div>
</div>
@endif
</div>