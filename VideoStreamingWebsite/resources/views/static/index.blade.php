@extends('master-frontend')
@section('title', $static->titlename)
@section('content')
<div class="main-content">
    <div class="container">
        <div class="page-404" style=" height: auto; margin-top: 15px;">
            <h2 style="color:#e39000; font-weight: bold">{{$static->titlename}}</h2>
            <div class="row content-image" style="text-align: justify; padding: 15px">
                <?= $static->content_page ?>
            </div>
        </div>
    </div>
</div>
<style type="text/css" media="screen">
    .page-404 h1{
        font-size: 30px;
        color:#e39000;
        padding-top:0px; 
    }
</style>
@endsection