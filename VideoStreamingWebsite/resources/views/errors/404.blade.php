@extends('master-frontend')
@section('title', 'Home')
@section('content')
<div class="main-content">
    <div class="container">
        <div class="page-404">
            <img src="{{asset('public/assets/images/404.jpg')}}">
            <div class="sorry-404"><span>Sorry :(</span> We couldn’t find this page.</div>
            <a href="{{action('home\HomeController@get_indexnew')}}" class="btn btn-signup">Back to homepage</a>
        </div>
    </div>
</div>
@endsection