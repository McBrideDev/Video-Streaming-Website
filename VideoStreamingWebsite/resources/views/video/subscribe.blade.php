@extends('master-frontend')
@section('title', 'Add Subscribe')
@section('content')
<div class="main-content page-channel">
    <div class="container">
        <h2><center>{{$msg}}</center></h2>
        @if(isset($channel))
        <div class="channel-bg">
            <h3>Related Channel For You <a href="{{URL('channel.html')}}">View more</a></h3>
            <div class="row main-channel">
                @foreach($channel as $result)
                <div class="col-xs-6 col-sm-6 col-md-3">
                    <div class="channel-col">
                        @if($result->poster !=NULL)
                        <img src="{{URL('public/upload/channel/')}}/{{$result->poster}}" width="277" height="111" alt="image" />
                        @else
                        <img src="{{URL('public/assets/images/bg_channels.jpg')}}" width="277" height="111" alt="image" />
                        @endif
                        <h4><a href="{{URL('channel.html&type=')}}{{$result->id}}">{{$result->title_name}}</a><span>{{$result->totalvideo}} Videos</span></h4>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
