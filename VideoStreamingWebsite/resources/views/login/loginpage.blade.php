@extends('master-frontend')
@section('title', 'Login page')
@section('content')
<?php
if (URL(\Request::path()) == URL(getLang() . 'member-proflie.html')) {
  $url_redirect = URL(getLang() . 'member-proflie.html?action=upload');
} else {
  $url_redirect = URL();
}
?>
<div class="main-content">
    <div class="container">

        <h2 style="background: none !important"></h2>
        <div class="row">
            <div class="col-sm-6 image-left">
                <div class="row">
                    <div class="col-md-12">
                        @if(isset($msglogin))
                        <h5 class="messageerror text-lowercase">{{$msglogin}}</h5>
                        @endif
                        <form action="{{URL(getLang().'login.html')}}" method="post">
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-user"></span></span>
                                <input type="text" name="email" value="" class="form-control" style="width:100%" placeholder="{{trans('home.ENTER_YOUR_EMAIL')}}" onclick="if (this.value == '') {
                                  this.value = ''
                                }" onblur="if (this.value == '') {
                                      this.value = ''
                                    }">
                            </div> <br />
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1"><span class="glyphicon glyphicon-eye-open"></span></span>
                                <input type="password" name="password" class="form-control" style="width:100%"  placeholder="***************">
                            </div>
                            <div class="clearfix"></div>
                            <div style="margin:20px auto;">
                                <input type="hidden" name="current_url" value="<?= $url_redirect ?>">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input class="btn btn-signup" type="submit" value="{{trans('home.LOGIN')}}" style="padding-left:30px ; padding-right:30px; margin:auto; width:100% " Name="login" />
                            </div>
                        </form>
                        <h5 class="messageerror"><span>{{trans('home.DO_NOT_HAVE_AN_ACCOUNT')}} ?</span><a href="javascript:void(0)" data-toggle="modal" data-target="#signup" title="{{trans('home.CLICK_HERE')}}"> {{trans('home.CLICK_HERE')}}</a></h5>
                        <h5 class="messageerror"><span>{{trans('home.FORGOT_PASSWORD')}} ?</span><a href="javascript:void(0)" data-toggle="modal" data-target="#forgot" title="{{trans('home.CLICK_HERE')}} !"> {{trans('home.CLICK_HERE')}}</a></h5>
                    </div>

                </div>
            </div>
            <div class="col-sm-6 image-right">
                <div class="ads-here-right">
                    <p class="advertisement">{{trans('home.ADVERTISEMENT')}}</p>
                    <?php echo StandardAdHome(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
