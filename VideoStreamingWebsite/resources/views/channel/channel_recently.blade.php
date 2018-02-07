@extends('master-frontend')
@section('title', trans('home.RECENTLY_UPDATED_CHANNEL'))
@section('content')

<div class="main-content page-channel">
    <div class="container-fluid ">
        <div class="titile-cate">
            <h1>{{trans('home.RECENTLY_UPDATED_CHANNEL')}}</h1>
        </div>
        <div id="result-filter">
            @if(count($channels)>0)
            <div class="channel-bg">
                <h3>{{trans('home.RECENTLY_UPDATE')}}</h3>
                <div class="row main-channel">
                    @foreach($channels as $result)
                    <?php $countvideo = count_video_in_channel($result->id); ?>
                    <div class="col-xs-6 col-sm-6 col-md-2">
                        <div class="channel-col">
                            <a href="{{URL(getLang().'channel')}}/{{$result->id}}/{{$result->post_name}}"><img src="{{$result->getImageUrl($result->poster)}}" class="img-responsive" alt="{{$result->title_name}}" height="150" /></a>
                            <h4><a href="{{URL(getLang().'channel')}}/{{$result->id}}/{{$result->post_name}}">{{$result->title_name}}</a><span>{{$countvideo}}</span></h4>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
        <div class="page_navigation">
            {!!$channels->render()!!}
        </div>

    </div>
</div>
@endsection
