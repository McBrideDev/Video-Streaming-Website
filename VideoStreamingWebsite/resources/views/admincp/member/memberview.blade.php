@extends('admincp.master')
@section('title',"Member")
@section('content')
<h4 class="alert_info">Welcome to Categories administrator </h4>
		<article class="module width_full">
			<header><h3>Member</h3></header>
				<form action="{{URL('admincp/edit-user/')}}/{{$member_edit->id}}" method="post" enctype="multipart/form-data" accept-charset="utf-8">
				
				<div class="module_content">
							@if($member_edit->avatar !=NULL )
							<img class="avatar" src="{{URL('public/upload/member/')}}/{{$member_edit->avatar}}" alt="">
							@else
							<img class="avatar" src="{{URL('public/upload/member/no_member.png')}}" alt="">
							@endif
							<div class="fileUpload btn btn-primary">
							    <i class="fa fa-camera"></i>
							    <input type="file" name="photo" class="upload" value="" />
							</div>
				
						<fieldset style="width:48%; float:left; margin-right: 10px">
							<label>First name</label>
							<input type="text" name='firstname'  style="width:90%" value="{{$member_edit->firstname}}">
						</fieldset>
						<fieldset  style="width:48%; float:left; margin-right: 10px">
							<label>Last name</label>
							<input type="text" name='lastname' style="width:90%" value="{{$member_edit->lastname}}">
						</fieldset>
						<div class="clear"></div>
						<fieldset style="width:48%; float:left; margin-right: 10px">
							<label>Birthdate</label>
							<input type="text" name='birthdate'  style="width:90%" value="{{$member_edit->birthdate}}">
						</fieldset>
						<fieldset  style="width:48%; float:left; margin-right: 10px">
							<label>Sex</label>
							<select name="sex">
								<option value="0" <?php if($member_edit->sex==0) echo "selected=selected"; ?>>Female</option>
								<option value="1" <?php if($member_edit->sex==1) echo "selected=selected"; ?>>Male</option>
							</select>
						</fieldset>

						<div class="clear"></div>
						<fieldset style="width:48%; float:left; margin-right: 10px">
							<label>Email</label>
							<input type="text" name='email'  style="width:90%" value="{{$member_edit->email}}">
						</fieldset>
						<fieldset  style="width:48%; float:left; margin-right: 10px">
							<label>Address</label>
							<input type="text" name='address' style="width:90%" value="{{$member_edit->address}}">
						</fieldset>
						<div class="clear"></div>
						<fieldset  style="width:48%; float:left; margin-right: 10px">
							<label>Member Upload Status</label>
							<input type="checkbox" name="upload_status" <?php if($member_edit->upload_status ==1) echo " checked=checked" ?>>
						</fieldset>
						<fieldset  style="width:48%; float:left; margin-right: 10px">
							<label>Member Embed Status</label>
							<input type="checkbox" name="embed_status" <?php if($member_edit->embed_status ==1) echo " checked=checked" ?>>
						</fieldset>	
						<div class="clear"></div>
					</div>
			<footer>
				<div class="submit_link">
					<select name="status">
						<option value="0" <?php if($member_edit->status==0) echo "selected=selected"; ?>>Draft</option>
						<option value="1" <?php if($member_edit->status==1) echo "selected=selected"; ?> >Published</option>
					</select>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="user_id" value="{{$member_edit->user_id}}" >
					<input type="submit" value="Publish" class="alt_btn">
					<input type="submit" value="Reset">
				</div>
			</footer>

			</form>
		</article><!-- end of post new article -->
		
		
		
		<div class="spacer"></div>
		

@endsection