@extends('master-frontend')
@section('title', 'Site map')
@section('content')

<div class="main-content">
    <div class="container">
        @if(isset($msglogin))
        {{$msglogin}}
        @else
        @endif

        <div class="row">
            <div id="col-md-12">
                <h2>Xstreamer {{trans('home.SITE_MAP')}}</h2>
                <div class="bonsai sitemap">
                    <ul class="section">
                        <li class="folder alpha open">
                            <a href="#"  style="text-transform: uppercase;">{{trans('home.VIDEOS')}}</a>
                            <ul class="section">
                                <li class=" alpha">
                                    <a href="{{URL(getLang().'video.html&action=new')}}">{{trans('home.NEWEST_VIDEOS')}}</a>
                                </li>
                                <li class=" alpha">
                                    <a href="{{URL(getLang().'top-rate.html')}}">{{trans('home.TOP_RATE_VIDEOS')}}</a>
                                </li>
                                <li class=" alpha">
                                    <a href="{{URL(getLang().'most-view.html')}}">{{trans('home.MOST_VIEWED_VIDEO')}}</a>
                                </li>
                                <li class=" alpha">
                                    <a href="{{URL(getLang().'video.html&action=most-favorited')}}">{{trans('home.MOST_FAVORITED_VIDEO')}}</a>
                                </li>
                                <li class=" alpha">
                                    <a href="{{URL(getLang().'video.html&action=most-commented')}}">{{trans('home.MOST_COMMENTED_VIDEO')}} </a>
                                </li>
                            </ul>
                        </li>
                        <li class="folder alpha open">
                            <a href="#"  style="text-transform: uppercase;">{{trans('home.CATEGORY')}}</a>
                            <ul class="section">
                                @foreach($categories as $result)
                                <li class="folder alpha">
                                    <a href="{{URL(getLang().'categories/')}}/{{$result->id}}.{{$result->post_name}}.html">{{$result->title_name}}</a>
                                    <ul class="section">
                                        <li data-action="new-video" data-name="{{$result->post_name}}" data-categories="{{$result->id}}" class="hidden-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-arrow-up"></i> {{trans('home.NEWEST_VIDEOS')}}</a></li>
                                        <li data-action="new-video" data-name="{{$result->post_name}}" data-categories="{{$result->id}}" class="visible-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-arrow-up"></i> {{trans('home.NEWEST_VIDEOS')}}</a></li>
                                        <li data-action="most-favorited" data-name="{{$result->post_name}}" data-categories="{{$result->id}}" class="hidden-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-thumbs-o-up"></i> {{trans('home.MOST_FAVORITED')}}</a></li>
                                        <li data-action="most-favorited" data-name="{{$result->post_name}}" data-categories="{{$result->id}}" class="visible-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-thumbs-o-up"></i> {{trans('home.FAVORITED')}}</a></li>
                                        <li data-action="most-rated" data-name="{{$result->post_name}}" data-categories="{{$result->id}}" class="hidden-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-star"></i> {{trans('home.TOP_RATE_VIDEOS')}}</a></li>
                                        <li data-action="most-rated" data-name="{{$result->post_name}}" data-categories="{{$result->id}}" class="visible-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-star"></i> {{trans('home.RATED')}}</a></li>
                                        <li data-action="most-viewed" data-name="{{$result->post_name}}" data-categories="{{$result->id}}" class="hidden-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-line-chart"></i> {{trans('home.MOST_VIEWED_VIDEO')}}</a></li>
                                        <li data-action="most-viewed" data-name="{{$result->post_name}}" data-categories="{{$result->id}}" class="visible-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-line-chart"></i> {{trans('home.MOST_VIEWED')}}</a></li>
                                        <li data-action="most-commented" data-name="{{$result->post_name}}" data-categories="{{$result->id}}" class="hidden-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-comments-o"></i> {{trans('home.MOST_COMMENTED_VIDEO')}}</a></li>
                                        <li data-action="most-commented" data-name="{{$result->post_name}}" data-categories="{{$result->id}}" class="visible-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-comments-o"></i> {{trans('home.COMMENTED')}}</a></li>
                                    </ul>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="alpha open">
                            <a href="{{URL(getLang().'premium-hd.html')}}"  style="text-transform: uppercase;">{{trans('home.PREMIUM_HD')}}</a>
                            <ul class="section"></ul>
                        </li>
                        <li class="alpha open">
                            <a href="{{URL(getLang().'top-rate.html')}}"  style="text-transform: uppercase;">{{trans('home.TOP_RATE_VIDEOS')}}</a>
                            <ul class="section"></ul>
                        </li>
                        <li class="alpha open">
                            <a href="{{URL(getLang().'most-view.html')}}"  style="text-transform: uppercase;">{{trans('home.MOST_VIEWED_VIDEO')}}</a>
                            <ul class="section"></ul>
                        </li>
                        <li class="folder alpha open">
                            <a href=""  style="text-transform: uppercase;">{{trans('home.CHANNEL')}}</a>
                            <ul class="section">
                                <li class="folder alpha open">
                                    <a href="#">{{trans('home.PORN_CHANNEL')}}</a>
                                    <ul class="section">
                                        @foreach($channel as $result)
                                        <li><a href="{{URL(getLang().'channel')}}/{{$result->id}}/{{$result->post_name}}">{{$result->title_name}}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li >
                                    <a href="{{URL(getLang().'channel-recently.html')}}">{{trans('home.RECENTLY_UPDATED_CHANNEL')}}</a>
                                </li>
                                <li >
                                    <a href="{{URL(getLang().'channel-subscriber.html')}}">{{trans('home.MOST_SUBSCRIBED_TO_CHANNEL')}}</a>
                                </li>
                                <li >
                                    <a href="{{URL(getLang().'channel-popular.html')}}">{{trans('home.MOST_POPULAR_CHANNELS')}}</a>
                                </li>
                            </ul>
                        </li>
                        <li class="alpha open">
                            <a href="{{URL(getLang().'porn-stars.html')}}"  style="text-transform: uppercase;">{{trans('home.PORNSTAR')}}</a>
                            <ul class="section"></ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection