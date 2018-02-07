<div class="channel-bg">
    @if(isset($filter))
    <h3><!-- Recently Updated <a href="#">View more</a> --></h3>
    <div class="row main-channel">
        @foreach($filter as $result)
        <?php $countvideo=count_video_in_channel($result->id); ?>
        <div class="col-xs-6 col-sm-6 col-md-2">
             <div class="channel-col">
                <a href="{{URL(getLang().'channel')}}/{{$result->id}}/{{$result->post_name}}"><img src="{{$result->getImageUrl($result->poster)}}" alt="{{$result->title_name}}" class="img-responsive" /></a>
                <h4><a href="{{URL(getLang().'channel')}}/{{$result->id}}/{{$result->post_name}}">{{$result->title_name}}</a><span> {{$countvideo}}</span></h4>
            </div>
        </div>
         @endforeach
    </div>
    <input type="hidden" id="channel-page-data" data-key="<?=isset($key)? $key :''?>" >
    <div id="channel-filter-page" class="nav-pagination">
        {!!$filter->render()!!}
    </div>
    @else
    <h3 style="text-align: center">{{$msg_filter}}</h3>
    @endif
</div>
