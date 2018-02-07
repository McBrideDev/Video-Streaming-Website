@extends('admincp.master')
@section('title',"Edit Existing User's Information ")
@section ('subtitle',"User Management")
@section('script')
<script type="text/javascript">

	function preview(input) {

		var file = input.files[0];
		if (file) $('#selectedFileName').text(file.name); else $('#selectedFileName').text('');
	}

</script>
@endsection
@section('content')
<div class="row ">
	<div class="modal-dialog col-md-12" style="width:100% !important">
		<div class="panel panel-primary">
			<div class="panel-heading">Edit {{$member_edit->username}}'s Information </div>
			<div class="panel-body">
				<form action="{{URL('admincp/edit-user/')}}/{{$member_edit->id}}" class="form-horizontal" method="post" enctype="multipart/form-data" accept-charset="utf-8">
					<div class="form-group">
						<div class="col-md-2"><label class="label-control">Profile's Image</label></div>
						<div class="col-md-10">
							@if($member_edit->avatar !=NULL )
							<img class="avatar" src="{{URL('public/upload/member/')}}/{{$member_edit->avatar}}" alt="" />
							@else
							<img class="avatar" src="{{URL('public/upload/member/no_member.png')}}" alt="" />
							@endif

							<div class="fileUpload ">
								<i class="fa fa-camera"></i>
								<input type="file" name="photo" class="upload" value="" onchange="preview(this);" />
							</div>
							<div>
								<label id="selectedFileName"></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="label-control required-field">First name</label></div>
						<div class="col-md-10 {{$errors->has('firstname')? 'has-error': ''}}">
							<input type="text" class="form-control" name='firstname'   value="{{$member_edit->firstname}}">
							<span class="required help-block">{{$errors->first('firstname')}}</span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="label-control required-field" >Last name</label></div>
						<div class="col-md-10 {{$errors->has('lastname')? 'has-error': ''}}">
							<input class="form-control" type="text" name='lastname'  value="{{$member_edit->lastname}}">
							<span class="required help-block">{{$errors->first('lastname')}}</span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="label-control">Birthdate</label></div>
						<div class="col-md-10"><input class="form-control" type="text" name='birthdate'  value="{{$member_edit->birthdate}}"></div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="label-control">Sex</label></div>
						<div class="col-md-3">
							<select name="sex" class="form-control">
								<option value="0" <?php if($member_edit->sex==0) echo "selected=selected"; ?>>Female</option>
								<option value="1" <?php if($member_edit->sex==1) echo "selected=selected"; ?>>Male</option>
							</select>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
						<div class="col-md-2"><label class="label-control required-field">Email</label></div>
						<div class="col-md-10 {{$errors->has('email')? 'has-error': ''}}">
							<input class="form-control" type="text" name='email'  value="{{$member_edit->email}}">
							<span class="required help-block">{{$errors->first('email')}}</span>
						</div>

						<!-- <div class="col-md-2"><label class="label-control">Address</label></div>
						<div class="col-md-4"><input class="form-control" type="text" name='address' value="{{$member_edit->address}}"></div> -->
					</div>
					<div class="clearfix"></div>
					<!-- <div class="form-group">
						<div class="col-md-2"><label class="label-control">Member Upload Status</label></div>
						<div class="col-md-4"><input  type="checkbox" name="upload_status" <?php if($member_edit->upload_status ==1) echo " checked=checked" ?>></div>

						<div class="col-md-2"><label class="label-control">Member Embed Status</label></div>
						<div class="col-md-4"><input  type="checkbox" name="embed_status" <?php if($member_edit->embed_status ==1) echo " checked=checked" ?>></div>
					</div>
					<div class="clearfix"></div> -->
					<div class="form-group">
						<div class="col-md-2"><label class="label-control">Status</label></div>
						<div class="col-md-10">
							<select name="status" class="form-control">
								<option value="0" <?php if($member_edit->status==0) echo "selected=selected"; ?>>Inactive</option>
								<option value="1" <?php if($member_edit->status==1) echo "selected=selected"; ?> >Active</option>
							</select>
						</div>
					</div>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="user_id" value="{{$member_edit->user_id}}" >
					<center><button type="submit"  class="btn btn-info">Update User's Information</button></center>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="spacer"></div>
@endsection
