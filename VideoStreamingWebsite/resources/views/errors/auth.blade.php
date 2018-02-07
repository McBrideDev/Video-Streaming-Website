@extends('master-frontend')
@section('title', 'Home')
@section('content')
<div class="main-content">
    <div class="container">
        <div class="page-auth">
            <img src="{{asset('public/assets/images/hand-stop.jpg')}}">
            <div class="sorry-auth"><span>Sorry! :( Please login to review the page you want!</span></div>
            <a href="{{action('login\LoginController@get_login')}}" class="btn btn-signup">Login</a>
        </div>
    </div>
</div>
@endsection