@extends('master-frontend')
@section('title', trans("home.CATEGORY"))
@section('content')
<div class="main-content categories_page">
	<div class="container-fluid pornstars_page pad-l-r-50">
		<div class="row">
			<div class="col-xs-12 col-sm-4 col-md-3 view_cat">
				<div class="view-cat-col">
					<h2><center>{{trans('home.CATEGORY')}}</center></h2>
					<ul>
						@foreach($categories as $result)
						<li><a href="{{URL(getLang().'categories/')}}/{{$result->id}}.{{$result->post_name}}.html">{{$result->title_name}}</a></li>
						@endforeach
					</ul>
				</div>
			</div>
			<div class="col-xs-12 col-sm-8 col-md-9 right_content pull-right pad-l-r-videoBox">
				@if(count($all_categories)>0)
				<div class="titile-cate">
					{{trans('home.PORN_CATEGORY')}}
				</div>
				<div class="row content-image">
					<!-- start -->
					@foreach($all_categories as $result)
					<div class="col-xs-6 col-sm-4 col-md-3 image-left">
						<div class="col">
							<div class="col_img">
								<a href="{{URL(getLang().'categories')}}/{{$result->id}}.{{$result->post_name}}.html">
									<img src="{{asset('public/upload/categories').'/'.$result->poster}}" alt="{{$result->title_name}}" class="img-responsive on-error-img" />
								</a>
							</div>
							<h3>
								<a href="{{URL(getLang().'categories')}}/{{$result->id}}.{{$result->post_name}}.html">{{str_limit($result->title_name,17)}}</a>
								{{-- <span>{{count_video_in_cat($result->id)}}</span> --}}
								<span>{{!empty($countVideoGroupByCategory[$result->id]) ? $countVideoGroupByCategory[$result->id] : 0}} Videos</span>
							</h3>
						</div>
					</div>
					@endforeach
				</div>
			</div>
			<div class="col-xs-12 col-sm-8 col-md-9 right_content pull-right pad-l-r-videoBox">
				<div class="titile-cate">{{trans('home.RECOMMENDED_CATEGORY_FOR_YOU')}}</div>
				<div class="row content-image">
					<!-- recomended -->
					@if(count($recomment_categories)>0)
					@foreach($recomment_categories as $result)
					<div class="col-xs-6 col-sm-4 col-md-3 image-left">
						<div class="col">
							<div class="col_img">
								<a href="{{URL(getLang().'categories')}}/{{$result->id}}.{{$result->post_name}}.html">
									<img src="{{asset('public/upload/categories').'/'.$result->poster}}" alt="{{$result->title_name}}" class="img-responsive on-error-img" />
								</a>
							</div>
							<h3>
								<a href="{{URL(getLang().'categories')}}/{{$result->id}}.{{$result->post_name}}.html">{{$result->title_name}}</a>
								{{-- <span>{{count_video_in_cat($result->id)}}</span> --}}
								<span>{{!empty($countVideoGroupByCategory[$result->id]) ? $countVideoGroupByCategory[$result->id] : 0}} Videos</span>
							</h3>
						</div>
					</div>
					@endforeach
					@endif
				</div>
			</div>
			<div class="clearfix"></div>
			<!-- end - recommended -->
			<div class="page_navigation">
				{!!$all_categories->render()!!}
			</div>
			<!-- end -->
			@endif

		</div>
	</div>
</div>

@endsection
