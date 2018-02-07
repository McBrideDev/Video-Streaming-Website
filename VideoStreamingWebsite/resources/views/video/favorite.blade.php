@extends('master-frontend')
@section('title', 'Add Favorite')
@section('content')
<div class="main-content page-channel">
    <div class="container">
        @if(isset($msg1))<h2><center>{{$msg1}}</center></h2>@endif
        @if(session('msg'))<h2><center> {{session('msg')}} </center></h2>@endif
        @if(isset($videorelated))
        <h3>Related video For You <a href="{{URL('video.html&action=all')}}">View more</a></h3>
        <div class="row">
            @foreach($videorelated as $result)
            <div class="col-xs-6 col-sm-3 image-left">
                <div class="col">
                    <div class="col_img">
                        <span class="hd">HD</span>
                        <a href="{{URL('watch')}}/{{$result->string_Id."/".$result->post_name}}.html">
                            <?php if ($result->poster == "") { ?>
                              <img src="{{URL('public/assets/images/no-image.jpg')}}" alt="{{$result->title_name}}"  height="177" />
                            <?php } else { ?>
                              <img src="{{$result->poster}}" alt="{{$result->title_name}}" height="177" />
                            <?php } ?>
                            <div class="position_text">
                                <p class="icon-like"></p>
                                <p class="percent">90%</p>
                                <p class="time_minimute">{{sec2hms($result->duration)}}</p>
                            </div>
                    </div>
                    <h3><a href="{{URL('watch')}}/{{$result->string_Id."/".$result->post_name}}.html">{{str_limit($result->title_name,30)}}</a></h3>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection