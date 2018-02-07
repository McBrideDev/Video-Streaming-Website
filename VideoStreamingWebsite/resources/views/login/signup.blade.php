<div id="signup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="min-width: 600px !important" >
    <div class="panel panel-primary">
      <div style="font-weight: bold;" class="panel-heading">{{trans('home.SIGN_UP')}}
        <div  id="loadsignup"></div>
      </div>
      <div class="panel-body">
        <div id="msgsignup" class="col-md-9"></div>
        <div class="clearfix"></div>
        <div class="row">
          <form name="register" role="form">
            <div class="col-md-6">
              <div class="form-group">
                <label for="firstname">{{trans('home.ENTER_FIRST_NAME')}}</label>
                <input type="text" class="form-control" name="firstname"  placeholder="{{trans('home.ENTER_FIRST_NAME')}}" required>

                <div id="firstname" class="alert-error"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="lastname">{{trans('home.ENTER_LAST_NAME')}}</label>
                <input type="text" class="form-control" name="lastname" placeholder="{{trans('home.ENTER_LAST_NAME')}}" required>
                <div id="lastname" class="alert-error"></div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="username">{{trans('home.USER_NAME')}}</label>
                <input type="text" class="form-control"  name="username" placeholder="{{trans('home.USER_NAME')}}" required>
                <div id="username" class="alert-error"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="passwords">{{trans('home.ENTER_YOUR_PASSWORD')}}</label>
                <input type="password" value="" class="form-control" name="passwords" placeholder="{{trans('home.ENTER_YOUR_PASSWORD')}}" required>
                <div id="password" class="alert-error"></div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="passwordagain">{{trans('home.ENTER_YOUR_CONFIRM_PASSWORD')}}</label>
                <input type="password" value="" class="form-control"  name="passwordagain" placeholder="{{trans('home.ENTER_YOUR_CONFIRM_PASSWORD')}}" required>
                <div id="passwordagains" class="alert-error"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="emails">{{trans('home.ENTER_YOUR_EMAIL')}}</label>
                <input type="email" class="form-control" value="" name="emails" placeholder="{{trans('home.ENTER_YOUR_EMAIL')}}" required>
                <div id="email" class="alert-error"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="Sex">{{trans('home.SEX')}}</label>
                <select name="sex" class="form-control" placeholder="{{trans('home.SEX')}}" required>
                  <option value="0" selected>Female</option>
                  <option value="1">Male</option>
                </select>
                <div id="sex" class="alert-error"></div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6 pull-right">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="button"  name="signupuser" id="signupuser" value="{{trans('home.SIGN_UP')}}" class="btn btn-signup pull-right"> .
              <input type="button" data-dismiss="modal" id="cancel"
                class="btn btn-signup pull-right" style="margin-right: 5px;" value="{{trans('home.CANCEL')}}">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('click', '#signupuser', function(e){
      $.ajax({
        url:"{{URL(getLang().'signup.html')}}",
        type:"POST",
        data:{
          'firstname':$('input[name=firstname]').val(),
          'lastname':$('input[name=lastname]').val(),
          'username':$('input[name=username]').val(),
          'sex':$('select[name=sex]').val(),
          'passwords':$('input[name=passwords]').val(),
          'passwordagain':$('input[name=passwordagain]').val(),
          'emails':$('input[name=emails]').val(),
          '_token':$('input[name=_token]').val()
        },
        success:function(data){
          if (data == 1){
            $('#firstname').html('<span class="glyphicon glyphicon-remove"></span> {{trans("home.FIRST_NAME_4_32_CHARACTERS")}}').fadeIn().delay(5000).fadeOut();
            $('input [name="fistname"]').focus();
          }
          if (data == 2){
            $('#firstname').html('<span class="glyphicon glyphicon-remove"></span> {{trans("home.FIRST_NAME_NOT_NULL")}}').fadeIn().delay(5000).fadeOut();
            $('input [name="fistname"]').focus();
          }
          if (data == 3){
            $('#lastname').html('<span class="glyphicon glyphicon-remove"></span> {{trans("home.LAST_NAME_4_32_CHARACTERS")}}').fadeIn().delay(5000).fadeOut();
            $('input[name="lastname"]').focus();
          }
          if (data == 4){
            $('#lastname').html('<span class="glyphicon glyphicon-remove"></span> {{trans("home.LAST_NAME_NOT_NULL")}}').fadeIn().delay(5000).fadeOut();
            $('input[name="lastname"]').focus();
          }
          if (data == 5){
            $('#username').html('<span class="glyphicon glyphicon-remove"></span> {{trans("home.USERNAME_EXITING")}}').fadeIn().delay(5000).fadeOut();
            $('input[name="username"]').focus();
          }
          if (data == 6){
            $('#password').html('<span class="glyphicon glyphicon-remove"></span> {{trans("home.PASSWORD_8_32_CHARACTERS")}} ').fadeIn().delay(5000).fadeOut();
            $('input[name="passwords"]').focus();
          }
          if (data == 7){
            $('#password').html('<span class="glyphicon glyphicon-remove"></span> {{trans("home.PASSWORD_NOT_NULL")}} ').fadeIn().delay(5000).fadeOut();
            $('input[name="passwords"]').focus();
          }
          if (data == 8){
            $('#passwordagains').html('<span class="glyphicon glyphicon-remove"></span> {{trans("home.CONFIRM_PASSWORD_NOT_NULL")}} ').fadeIn().delay(5000).fadeOut();
            $('input[name="passwordagain"]').focus();
          }
          if (data == 9){
            $('#passwordagains').html('<span class="glyphicon glyphicon-remove"></span> {{trans("home.CONFIRM_PASSWORD_NOT_MATCH")}} ').fadeIn().delay(5000).fadeOut();
            $('input[name="passwordagain"]').focus();
          }
          if (data == 10){
            $('#email').html('<span class="glyphicon glyphicon-remove"></span> {{trans("home.EMAIL_EXITING")}} ').fadeIn().delay(5000).fadeOut();
            $('input[name="emails"]').focus();
          }
          if (data == 13){
            $('#email').html('<span class="glyphicon glyphicon-remove"></span> {{trans("home.EMAIL_NOT_NULL")}} ').fadeIn().delay(5000).fadeOut();
            $('input[name="emails"]').focus();
          }
          if (data == 14){
            $('#email').html('<span class="glyphicon glyphicon-remove"></span>{{trans("home.EMAIL_FORMAT")}}').fadeIn().delay(5000).fadeOut();
            $('input[name="emails"]').focus();
          }
          if (data == 11){
            $('#username').html('<span class="glyphicon glyphicon-remove"></span> {{trans("home.USERNAME_NOT_NULL")}} ').fadeIn().delay(5000).fadeOut();
            $('input[name="username"]').focus();
          }
          if (data == 12){
            $('#username').html('<span class="glyphicon glyphicon-remove"></span> {{trans("home.USERNAME_8_32_CHARACTERS")}}r ').fadeIn().delay(5000).fadeOut();
            $('input[name="username"]').focus();
          }
          if (data == 0){
            $('#signup').hide(); $('.modal-backdrop').css('display', 'none');
            $('#msg-popup').modal('show');
          }
        }
      });
    });
  });
</script>