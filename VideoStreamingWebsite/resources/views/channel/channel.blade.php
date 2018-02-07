@extends('master-frontend')
@section('title', 'Channel')
@section('content')
<div class="main-content page-channel">
  <div class="container-fluid ">
    <div class="titile-cate">
      <h1>{{trans('home.PORN_CHANNEL')}}</h1>
      <div class="rate-filter">
        <span>{{trans('home.BROWSE_BY_NAME')}}:</span>
        <ul id="fillter">
          <li><a data="specialKey" href="javascript:void(0);">#</a></li>
          <li><a data="A" href="javascript:void(0);">A</a></li>
          <li><a data="B" href="javascript:void(0);">B</a></li>
          <li><a data="C" href="javascript:void(0);">C</a></li>
          <li><a data="D" href="javascript:void(0);">D</a></li>
          <li><a data="E" href="javascript:void(0);">E</a></li>
          <li><a data="F" href="javascript:void(0);">F</a></li>
          <li><a data="G" href="javascript:void(0);">G</a></li>
          <li><a data="H" href="javascript:void(0);">H</a></li>
          <li><a data="I" href="javascript:void(0);">I</a></li>
          <li><a data="J" href="javascript:void(0);">J</a></li>
          <li><a data="K" href="javascript:void(0);">K</a></li>
          <li><a data="L" href="javascript:void(0);">L</a></li>
          <li><a data="M" href="javascript:void(0);">M</a></li>
          <li><a data="N" href="javascript:void(0);">N</a></li>
          <li><a data="O" href="javascript:void(0);">O</a></li>
          <li><a data="P" href="javascript:void(0);">P</a></li>
          <li><a data="Q" href="javascript:void(0);">Q</a></li>
          <li><a data="R" href="javascript:void(0);">R</a></li>
          <li><a data="S" href="javascript:void(0);">S</a></li>
          <li><a data="T" href="javascript:void(0);">T</a></li>
          <li><a data="U" href="javascript:void(0);">U</a></li>
          <li><a data="V" href="javascript:void(0);">V</a></li>
          <li><a data="W" href="javascript:void(0);">W</a></li>
          <li><a data="X" href="javascript:void(0);">X</a></li>
          <li><a data="Y" href="javascript:void(0);">Y</a></li>
          <li><a data="Z" href="javascript:void(0);">Z</a></li>
          <li><a data="all" class="letter"  onclick="javascript:window.location.reload()" href="javascript:void(0);">{{trans('home.ALL')}}</a></li>
        </ul>
      </div>
    </div>
    <div id="result-filter">

      @if(count($channel_recently)>0)
      <div class="channel-bg">
        <h3><i class="fa fa-play-circle-o" aria-hidden="true"></i> {{trans('home.RECENTLY_UPDATED_CHANNEL')}} <a href="{{URL(getLang().'channel-recently.html')}}">{{trans('home.VIEW_MORE')}} <span class="glyphicon glyphicon-plus"></span></a></h3>
        <div class="row main-channel">
          @foreach($channel_recently as $result)
          <div class="col-md-2 col-sm-3 col-xs-6 chwrap">
            <div class="channel-col">
              <a href="{{URL(getLang().'channel')}}/{{$result->id}}/{{$result->post_name}}"><img class="img-responsive" src="{{$result->getImageUrl($result->poster)}}" alt="{{$result->title_name}}" height="150" /></a>
              <h4><a href="{{URL(getLang().'channel')}}/{{$result->id}}/{{$result->post_name}}">{{$result->title_name}}</a><span>{{isset($countVideoBaseChannelId[$result->id]) ? $countVideoBaseChannelId[$result->id] : 0}} Videos</span></h4>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endif

      @if(count($channelsubscriber)>0)
      <div class="channel-bg">
        <h3><i class="fa fa-play-circle-o" aria-hidden="true"></i> {{trans('home.MOST_SUBSCRIBED_TO_CHANNEL')}} <a href="{{URL(getLang().'channel-subscriber.html')}}">{{trans('home.VIEW_MORE')}} <span class="glyphicon glyphicon-plus"></span></a></h3>
        <div class="row main-channel">
          @foreach($channelsubscriber as $result)
          <div class="col-md-2 col-sm-3 col-xs-6 chwrap">
            <div class="channel-col">
              <a href="{{URL(getLang().'channel')}}/{{$result->id}}/{{$result->post_name}}"><img class="img-responsive" src="{{$result->getImageUrl($result->poster)}}" alt="{{$result->title_name}}"  height="150" /></a>
              <h4><a href="{{URL(getLang().'channel')}}/{{$result->id}}/{{$result->post_name}}">{{$result->title_name}}</a><span>{{isset($countVideoBaseChannelId[$result->id]) ? $countVideoBaseChannelId[$result->id] : 0}} Videos</span></h4>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endif
      @if(count($channelpopular)>0)
      <div class="channel-bg">
        <h3><i class="fa fa-play-circle-o" aria-hidden="true"></i> {{trans('home.MOST_POPULAR_CHANNELS')}}<a href="{{URL(getLang().'channel-popular.html')}}">{{trans('home.VIEW_MORE')}} <span class="glyphicon glyphicon-plus"></span></a></h3>
        <div class="row main-channel">
          @foreach($channelpopular as $result)
          <div class="col-xs-6 col-sm-3 col-md-2 chwrap">
            <div class="channel-col">
              <a href="{{URL(getLang().'channel')}}/{{$result->id}}/{{$result->post_name}}"><img class="img-responsive" src="{{$result->getImageUrl($result->poster)}}" alt="{{$result->title_name}}" height="150" /></a>
              <h4><a href="{{URL(getLang().'channel')}}/{{$result->id}}/{{$result->post_name}}">{{$result->title_name}}</a><span>{{isset($countVideoBaseChannelId[$result->id]) ? $countVideoBaseChannelId[$result->id] : 0}} Videos</span></h4>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection
