@extends('master-frontend')
@section('title', 'Categories')
@section('content')
<div class="main-content categories_page">
    <div class="container pad-l-r-50">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 view_cat">
                <div class="view-cat-col">
                    <h2>View A Category</h2>
                    <ul>
                        @foreach($categories as $result)
                        <li><a href="{{URL('categories/')}}/{{$result->ID}}.{{$result->post_name}}.html">{{$result->title_name}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10 right_content pad-l-r-videoBox">
                <h2>Country {{$country->name}}</h2>
                <div class="row">
                    <div class="col-md-12 image-left">

                        @if(count($videoin)>0)
                        @foreach($videoin as $result)
                        <?php $rating = \App\Models\RatingModel::get_percent($result->string_Id); ?>
                        <div class="col-xs-6 col-md-3" style="margin-bottom: 10px;">
                            <div class="col">
                                <div class="col_img">
                                    <span class="hd">HD</span>
                                    <a href="{{URL('watch')}}/{{$result->string_Id."/".$result->post_name}}.html">
                                        <?php if ($result->poster == NULL) { ?>
                                          <img src="{{URL('public/assets/images/no-image.jpg')}}" alt="{{$result->title_name}}" class="img-responsive" />
                                        <?php } else { ?>
                                          <img src="{{$result->poster}}" alt="{{$result->title_name}}" class="img-responsive" />
                                        <?php } ?>
                                        <div class="position_text">
                                            <p class="icon-like"></p>
                                            <p class="percent">{{floor($rating['percent_like'])}}%</p>
                                            <p class="time_minimute">{{$result->duration}}</p>
                                        </div>
                                </div>
                                <h3><a href="{{URL('watch')}}/{{$result->string_Id."/".$result->post_name}}.html">{{str_limit($result->title_name,25)}}</a></h3>
                            </div>
                        </div>
                        @endforeach
                        @else
                        {{$message}}
                        @endif

                    </div>
                </div>
            </div>
        </div>
        @if(count($country_category)>0)
        <div class="titile-cate popular">Popular by Country</div>
        <div class="row content_popular">
            <?php $i = 1 ?>

            <div class="col-md-12">
                <ul>
                    @foreach($country_category as $result)
                    <?php $check_video = \App\Models\VideoModel::where('categories_Id', '=', $result->ID)->count(); ?>
                    @if($check_video>0)
                    <li class="col-xs-12 col-sm-4 col-md-4 col-lg-2"><a data-toggle="tooltip" data-placement="top" title="{{$result->name}}" href="{{URL('country')}}/{{$result->id}}/{{str_slug($result->name)}}"><img src="{{URL('public/assets/flags/')}}/{{$result->alpha_2}}.png" alt="{{$result->name}}" /> <span class="hidden-xs">{{str_limit($result->name,10)}}</span></a></li>
                    @endif
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
        @if(count($channel_popular)>0)
        <div class="channel-content">
            <h4 class="titile-cate popular">MOST POPULAR CHANNELS</h4>
            <div class="row main-channel">
                @foreach($channel_popular as $result)
                <?php $countvideo = \App\Models\VideoModel::where('channel_Id', '=', $result->ID)->count() ?>
                <div class="col-20">
                    <div class="channel-col">
                        <a href="{{URL('channel')}}/{{$result->ID}}/{{$result->post_name}}"><img src="{{$result->getImageUrl($result->poster)}}" class="img-responsive" alt="{{$result->title_name}}" /></a>
                        <h4><a href="{{URL('channel')}}/{{$result->ID}}/{{$result->post_name}}">{{$result->title_name}}</a><span> {{$countvideo}} Videos</span></h4>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
<script type="text/javascript">
  $(function () {
      $('[data-toggle="tooltip"]').tooltip()
  })
</script>
@endsection
