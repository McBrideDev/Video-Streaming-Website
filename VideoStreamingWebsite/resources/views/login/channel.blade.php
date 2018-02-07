<div id="msgloading"></div>
<div class="member-profile">
	<h2>{{trans('home.YOUR_EDIT_CHANNEL')}}</h2>

		<div class="col-md-12" style="background: #2c2d2f">

					<form  accept-charset="utf-8" enctype="multipart/form-data"  >
                  <div id="show-error">
                    
                  </div>
						        <div class="form-group">
	                    <label >{{trans('home.ENTER_CHANNEL_NAME')}}</label>
                    	  <div id="titlename" class="alert-error"></div>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{$channel->title_name}}" name="titlename" id="titlename" placeholder="Enter First Name" required>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                        </div>
		                </div>
						
		                <div class="form-group">
	                    <label >{{trans('home.UPLOAD_IMAGE')}}</label>
                      <div class="input-group">
                          <input type="file" name="file_upload"  id="file_upload" />
                      </div>
                      <div id="upload-msg" class="input-group"></div>
		                </div>
                    <div class="form-group">
                      <label >{{trans('home.DESCRIPTION')}}</label>
                      <div class="input-group">
                          <textarea class="form-control" style="min-height: 100px" name="description" id="description">{{$channel->description}}</textarea>
                          <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                          <label >{{trans('home.SUBSCRIBE')}} </label>
                          <input type="checkbox"   class="margin-l10"  name="subcribe" <?=($channel->subscribe_status==1)? 'checked="checked"':'' ?> value=""   />
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="input-group">
                          <label >{{trans('home.STATUS')}} </label>
                          <select name="status" class="form-control" style="width: 100%">
                            <option value="2" <?=($channel->status==2)? 'selected="selected"':'' ?> >{{trans('home.PUBLISH')}}</option>
                            <option value="1" <?=($channel->status==1)? 'selected="selected"':'' ?> >{{trans('home.UN_PUBLISH')}}</option>
                          </select>
                      </div>
                    </div>

		                <div class="form-group">
	                    <label >{{trans('home.TAGS')}}</label>
                      <div class="input-group">
                          <textarea class="form-control" style="min-height: 100px" name="tag" id="tag">{{$channel->tag}}</textarea>
                          <span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
                      </div>
		                </div>
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" id="file_hidden" name="file_hidden" value="">
                    <input type="hidden" name="id" value="{{$channel->id}}">
		                <input type="hidden" name="user" value="{{$channel->user_id}}">
						<center><input type="button" id="updatemember" style="margin-bottom: 15px" class="btn btn-signup"  value="Update"></center>
					</form>


	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('click','#updatemember',function (e){

			e.preventDefault();
          $.ajax({
            url:"{{URL(getLang().'channel-edit.html')}}",
            type:"POST",
            data:{
                'user':$('input[name=user]').val(),
                'titlename':$('input[name=titlename]').val(),
                'description':$('textarea[name=description]').val(),
                'tag':$('textarea[name=tag]').val(),
                'file':$('input[name=file_hidden]').val(),
                'subcribe':$('input:checkbox:checked').val(),
                'status':$('select[name=status]').val(),
                '_token':$('input[name=_token]').val()
              },success:function(data){
                  $('#show-error').empty().append(data);
              },beforeSend:function(){
                $('#setting-load').html("<img src='{{URL('public/assets/images/result_loading.gif')}}'/>").show();
              },complete:function(){
                 $('#setting-load').html("<img src='{{URL('public/assets/images/result_loading.gif')}}'/>").hide();
              }
          });
    });
	});
</script>
<script type="text/javascript">
    $(function() {
    $('#file_upload').uploadify({
        'multi'    : false,
        'auto'     : true,
        'fileTypeDesc' : 'Image Files',
        'fileTypeExts' : '*.gif; *.jpg; *.png; *.PNG; *.JPG; *.GIF;',
        'checkExisting' : '{{URL("public/assets/misc/check-exists-font-end-channel.php")}}',
        'swf': '{{URL("public/assets/misc/uploadify.swf")}}',
        'uploader' : '{{URL("public/assets/misc/upload_fontend_channel.php")}}',
        'uploadLimit' : 1,
        'onSelect' : function(file) {
           document.getElementById("file_hidden").value=file.name;
        },
        'onUploadSuccess' : function(file) {
          $('#upload-msg').html('<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><strong> Channel poster is upload successfully!. Please submit button to update  profile</strong></div>').fadeIn().delay(3000).fadeOut();
        }
    });
});
</script>