<div id="msgloading"></div>
<div class="member-profile">
	<h2>{{trans('home.EDIT_YOUR_PROFILE')}}</h2>
	<div class="col-md-9 edit-profile-wrap" style="background: #2c2d2f">
		<form  accept-charset="utf-8" enctype="multipart/form-data" id="formEditProfile" >
			<div class="form-group">
				<label >{{trans('home.FIRST_NAME')}}</label>
					<div id="updatefirstname" class="alert-error"></div>
					<div class="input-group">
						<input type="text" class="form-control" value="{{$user->firstname}}" name="updatefirstname" id="updatefirstname" placeholder="Enter First Name" required>
						<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
					</div>
			</div>
			<div class="form-group">
				<label >{{trans('home.LAST_NAME')}}</label>
				<div id="updatelastname" class="alert-error"></div>
					<div class="input-group">
						<input type="text" class="form-control" value="{{$user->lastname}}" name="updatelastname" id="updatelastname" placeholder="Enter Last Name" required>
						<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
					</div>
			</div>
			<div class="form-group">
				<label >{{trans('home.EMAIL')}}</label>
				<div id="updateemails" class="alert-error"></div>
					<div class="input-group">
						<input type="email" class="form-control" value="{{$user->email}}"  name="updateemails" id="updateemails" placeholder="youremail@email.com" required>
						<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
					</div>
			</div>
			<div class="form-group">
				<label >Address</label>
					<div class="input-group">
						<input type="address" class="form-control" value="{{$user->address}}" name="updateaddress" id="updateaddress" placeholder="Your address" required>
						<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
					</div>
			</div>
			<div class="form-group">
				<label >{{trans('home.BIRTHDATE')}}</label>
					<div class="input-group">
						<input type="date" class="form-control" value="{{$user->birthdate}}" name="updatebirthdate" id="updatebirthdate" placeholder="" required>
						<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
					</div>
			</div>
			<div class="form-group">
				<label >{{trans('home.UPLOAD_IMAGE')}}</label>
					<div class="input-group">
						<input type="file" name="file_upload"  id="file_upload" accept="image/*" />
					</div>
				<div id="upload-msg" class="input-group">

				</div>
			</div>
			<div class="form-group">
				<label >{{trans('home.BIO')}}</label>
					<div class="input-group">
						<textarea class="form-control" style="min-height: 100px" name="updatebio" id="updatebio">{{$user->bio}}</textarea>
						<span class="input-group-addon"><span class="glyphicon glyphicon-asterisk"></span></span>
					</div>
			</div>
			<div class="form-group">
				<input type="hidden" id="file_hidden" name="file_hidden">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<center><input type="button" id="updatemember" class="btn btn-signup"  value="Update"></center>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
	$(document).on('click','#updatemember',function (e){
		e.preventDefault();
		$.ajax({
			url:"{{URL(getLang().'member-edit.html')}}",
			type:"POST",
			data:{
				'firstname':$('input[name=updatefirstname]').val(),
				'lastname':$('input[name=updatelastname]').val(),
				'emails':$('input[name=updateemails]').val(),
				'birthdate':$('input[name=updatebirthdate]').val(),
				'address':$('input[name=updateaddress]').val(),
				'is_comment':$('input[name=is_comment]').val(),
				'bio':$('textarea[name=updatebio]').val(),
				'file':$('input[name=file_hidden]').val(),
				'_token':$('input[name=_token]').val()
			},success:function(data){
				$('#msgloading').html('<div class="alert alert-pink"><span class="fa fa-check"></span><strong> '+ data.message+'</strong></div>').show();
			},beforeSend:function(){
				$('#setting-load').html("<img src='{{URL('public/assets/images/result_loading.gif')}}'/>").show();
			},complete:function(){
				$('#setting-load').html("<img src='{{URL('public/assets/images/result_loading.gif')}}'/>").hide();
			},error:function(data){
				if(data.responseJSON.status_code === 422){
					console.log(data.responseJSON.message);
					$('#msgloading').empty().append('<div class="alert alert-danger"><span class="fa fa-check"></span><strong>'+data.responseJSON.message+'</strong></div>').show();
				}
				if(data.responseJSON.status_code === 401){
					$('#myModal').modal('show');
				}
			}
		});
	});

	$(document).on('change','#file_upload',function (e){
		var data = new FormData();
		$.each(jQuery('#file_upload')[0].files, function(i, file) {
		    data.append(i, file);
		});

		$.ajax({
			url:"{{URL(getLang()."upload-member-avatar")}}",
			type:"POST",
			contentType: false,
		    processData: false,
			data: data,
	        headers: {
	           'X-CSRF-Token': $('form#formEditProfile [name="_token"]').val()
	        },
			success:function(data){
				$('#upload-msg').html('<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><strong>{{trans("home.UPDATED_SUCCESSFULLY")}}</strong></div>').fadeIn().delay(3000).fadeOut();
			},error: function (request, status, error) {
				$('#upload-msg').html(
					`<div class="alert alert-danger">
						<span class="glyphicon glyphicon-remove"></span>
						<strong>`+request.responseJSON.error+`</strong>
					</div>`
				).fadeIn().delay(3000).fadeOut();

		    },
		});
	});

	// $('#file_upload').uploadify({
	// 	'multi'    : false,
	// 	'auto'     : true,
	// 	'fileObjName': 'memberAvatar',
	// 	'formData'      : {'_token' : '{{csrf_token()}}'},
	// 	'buttonText' : 'Select Your Avatar ',
	// 	'buttonClass' : 'btn-avatar',
	// 	'fileTypeDesc' : 'Image Files',
	// 	'fileTypeExts' : '*.gif; *.jpg; *.png; *.PNG; *.JPG; *.GIF;',
	// 	'swf': '{{URL("public/assets/misc/uploadify.swf")}}',
	// 	'uploader' : '{{URL(getLang()."upload-member-avatar")}}',
	// 	'onSelect' : function(file) {
	// 	   document.getElementById("file_hidden").value=file.name;
	// 	},
	// 	'onUploadSuccess' : function(file) {
	// 	  $('#upload-msg').html('<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><strong>{{trans("home.UPDATED_SUCCESSFULLY")}}</strong></div>').fadeIn().delay(3000).fadeOut();
	// 	}
	// });

</script>
