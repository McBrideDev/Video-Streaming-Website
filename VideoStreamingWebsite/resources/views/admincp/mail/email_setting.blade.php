@extends('admincp.master')
@section('title',"Email Settings")
@section ('subtitle',"Settings")
@section('content')
<div class="row ">
		<div class="modal-dialog col-md-12" style="width:100% !important">
		    @if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
			@if(session('msgerro'))<div class="alert alert-danger">{{session('msgerro')}}</div>@endif
       		<div class="panel panel-primary">
               	<div class="panel-heading">Email Settings </div>
	                <div class="panel-body">
				<form action="{{URL('admincp/email-setting')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
						
						<div class="form-group">
							<div class="col-md-4"><label class="control-label">Admin Change Password</label></div>
							<div class="col-md-8">
								<select name="admin_forgot_password_email" class="form-control">
									<option>Select templete</option>
									@foreach($temp as $result)
									<option value="{{$result->id}}" <?=($email_setting->admin_forgot_password_email==$result->id)? 'selected="selected"' :""  ?>  >{{$result->name}}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-4"><label class="control-label">Member Forgot Password</label></div>
							<div class="col-md-8">
								<select name="member_forgot_password_email" class="form-control">
									<option>Select templete</option>
									@foreach($temp as $result)
									<option value="{{$result->id}}"  <?=($email_setting->member_forgot_password_email==$result->id)? 'selected="selected"' :""  ?> >{{$result->name}}</option>
									@endforeach 
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4"><label class="control-label">Confirm Signup</label></div>
							<div class="col-md-8">
								<select name="registration_email" class="form-control">
										<option>Select templete</option>
										@foreach($temp as $result)
										<option value="{{$result->id}}"  <?=($email_setting->registration_email==$result->id)? 'selected="selected"' :""  ?>  >{{$result->name}}</option>
										@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4"><label class="control-label">Channel Subscription Welcome </label></div>
							<div class="col-md-8">
								<select name="channel_subscriber_email" class="form-control">
										<option>Select templete</option>
										@foreach($temp as $result)
										<option value="{{$result->id}}" <?=($email_setting->channel_subscriber_email==$result->id)? 'selected="selected"' :""  ?> >{{$result->name}}</option>
										@endforeach
								</select>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-4"><label class="control-label">Channel Register Email</label></div>
							<div class="col-md-8">
								<select name="channel_register_email" class="form-control">
										<option>Select templete</option>
										@foreach($temp as $result)
										<option value="{{$result->id}}" <?=($email_setting->channel_register_email==$result->id)? 'selected="selected"' :""  ?> >{{$result->name}}</option>
										@endforeach
								</select>
							</div>
						</div>
						
						<div class="form-group">
							
								<input type="hidden" name="id" value="{{$email_setting->id}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
							
							<center><button type="submit" value="Publish" class="btn btn-info">Save</button></center>
						</div>

			</form>
		</article><!-- end of post new article -->
		
		
		
		<div class="spacer"></div>
		

@endsection