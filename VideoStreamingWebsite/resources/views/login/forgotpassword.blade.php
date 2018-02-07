<div id="forgot" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-dialog" >
   <div class="panel panel-primary">
     <div class="panel-heading">{{trans('home.FORGOT_PASSWORD')}}</div>
     <div class="panel-body">
      <form name="forgotpassword" role="form">
        <div class="col-md-12">
          <div class="form-group">
            <label for="firstname">{{trans('home.ENTER_YOUR_EMAIL')}}</label>
            <div id="email-error" class="alert-error"></div> 
            <div id="msg-success"></div> 
            <input type="email" class="form-control" name="emailforgot" id="email" placeholder="{{trans('home.ENTER_YOUR_EMAIL')}}" required>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12">  
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="button"   id="send-mail-forgot-password" value="{{trans('home.SEND_MAIL')}}" class="btn btn-signup pull-right">
          <input type="button" data-dismiss="modal" id="Close" class="btn btn-signup pull-right" style="margin-right: 5px;" value="{{trans('home.CLOSE')}}">   
        </div>
      </form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript"> $(document).ready(function(){$(document).on('click','#send-mail-forgot-password',function(e){$.ajax({url: base_url+"forgot-password.html", type:"POST", data:{'email':$('input[name=emailforgot]').val(), '_token':$('input[name=_token]').val(), },success:function(data){$('#msg-success').empty().html('<div class="alert fade in alert-success" ><i class="fa fa-times" data-dismiss="alert"></i>'+data.responseJSON.message+'</div>').show(); },error:function(data){$('#msg-success').empty().html('<div class="alert fade in alert-danger" ><i class="fa fa-times" data-dismiss="alert"></i> '+data.responseJSON.message+'</div>'); } }); }); }); </script>