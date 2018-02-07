@extends('admincp.master')
@section('title',"Add A New User")
@section ('subtitle',"User Management")
@section('content')

<script type="text/javascript">

	function preview(input) {

		var file = input.files[0];
		console.log(file,'file');
		if (file) $('#selectedFileName').text(file.name); else $('#selectedFileName').text('');
	}

</script>

<div class="row ">
				<div class="modal-dialog col-md-12" style="width:100% !important">
					@if(session('msg'))<div data-dismis="close" class="alert alert-danger"><i class="fa fa-close close"></i>{{session('msg')}}</div>@endif
					<div class="panel panel-primary">
						<div class="panel-heading">Add A New User</div>
							<div class="panel-body">
				<form action="{{URL('admincp/add-member/')}}" class="form-horizontal" method="post" enctype="multipart/form-data" accept-charset="utf-8">
						<div class="form-group ">
							<div class="col-md-2"><label class="label-control">Profile's Image</label></div>
							<div class="col-md-4 {{$errors->has('avatar')? 'has-error': ''}}">
								<img class="avatar" src="{{URL('public/upload/member/no_member.png')}}" alt="">

								<div class="fileUpload">
									<i class="fa fa-camera"></i>
									<input type="file" name="photo" class="upload" value="" onchange="preview(this);"" />
								</div>
								<div>
									<label id="selectedFileName"></label>
								</div>
							</div>
						</div>

						<div class="form-group ">
							<div class="col-md-2"><label class="label-control required-field">First name</label></div>
							<div class="col-md-4 {{$errors->has('firstname')? 'has-error': ''}}">
								<input type="text" class="form-control" name='firstname' value="{{old('firstname')}}">
								<span class="required help-block">{{$errors->first('firstname')}}</span>
							</div>

							<div class="col-md-2"><label class="label-control required-field" >Last name</label></div>
							<div class="col-md-4 {{$errors->has('lastname')? 'has-error': ''}}">
								<input class="form-control" type="text" name='lastname'  value="{{old('lastname')}}">
								<span class="required help-block">{{$errors->first('lastname')}}</span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="label-control required-field">Username</label></div>
							<div class="col-md-4 {{$errors->has('username')? 'has-error': ''}}">
								<input type="text" class="form-control" name='username'   value="{{old('username')}}">
								<span class="required help-block">{{$errors->first('username')}}</span>
							</div>
							<div class="col-md-2"><label class="label-control required-field">Email</label></div>
							<div class="col-md-4 {{$errors->has('email')? 'has-error': ''}}">
								<input class="form-control" type="text" name='email'  value="{{old('email')}}">
								<span class="required help-block">{{$errors->first('email')}}</span>
							</div>

						</div>
						<div class="clearfix"></div>
						<div class="form-group">
							<div class="col-md-2"><label class="label-control required-field" >Password</label></div>
							<div class="col-md-4 {{$errors->has('password')? 'has-error': ''}}">
								<input class="form-control" type="password" name='password'  value="">
								<span class="required help-block">{{$errors->first('password')}}</span>
							</div>
							<div class="col-md-2"><label class="label-control required-field" >Confirm Password</label></div>
							<div class="col-md-4 {{$errors->has('password_confirmation')? 'has-error': ''}}">
								<input class="form-control" type="password" name='password_confirmation'  value="">
								<span class="required help-block">{{$errors->first('password_confirmation')}}</span>
							</div>
						</div>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<center><button type="submit"  class="btn btn-info">Save User</button></center>
			</form>
			</div>
		</div>
	</div>
</div>



		<div class="spacer"></div>


@endsection
