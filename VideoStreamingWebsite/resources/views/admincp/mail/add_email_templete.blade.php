@extends('admincp.master')
@section('title',"Email Template")
@section('content')
<div class="row ">
		<div class="modal-dialog col-md-12" style="width:100% !important">
		    @if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
			@if(session('msgerro'))<div class="alert alert-danger">{{session('msgerro')}}</div>@endif
       		<div class="panel panel-primary">
               	<div class="panel-heading">Add Email Template</div>
	                <div class="panel-body">
				<form action="{{URL('admincp/add-email-templete')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				
			
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Name</label></div>
							<div class="col-md-10"><input class="form-control" type="text" name="name" value="" placeholder=""></div>
						</div>
						

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Description</label></div>
							<div class="col-md-10">
								<input class="form-control" type="text" name="description" value="" placeholder="">
								<!--<select name="temp_group" class="form-control" >
									<option >Select Group templete</option>
									<option value="1" >Channel Subscriber</option>
									<option value="2" >Forgot Password</option>
									<option value="3" >Subscription</option>
									<option value="4" >Comfirm Signup</option>
									<option value="5" >Private Message</option>
									<option value="6" >Reply Comment</option>
								</select> -->
							</div>
						</div>

						<div class="form-group">
							
							<div class="col-md-2"><label class="control-label">Content Mail</label></div>
							<div class="col-md-10">
								<span>Global element: <span class="label label-info"> First name: {{$firstname}}</span>,<span class="label label-info">Last name: {{$lastname}}</span>,<span class="label label-info">New password: {{$newpassword}}</span>,<span class="label label-info">Token: {{$token}}</span>,<span class="label label-info">Email: {{$email}}</span>,<span class="label label-info">Content: {{$content}}</span>,<span class="label label-info"> Site name: {{$site_name}}</span>,<span class="label label-info"> Site email: {{$site_email}}</span>,<span class="label label-info"> Site Phone: {{$site_phone}}</span>,<span class="label label-info"> Channel name: {{$channel_name}}</span></span>
								<textarea  name="content" rows="15" id="email-temmple" class="form-control wysiwyg"></textarea>
							</div>

						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Status</label></div>
							<div class="col-md-10">
								<select class="form-control" name="status">
									<option value="1">Active</option>
									<option value="0">Block</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2">
								<input type="hidden" name="id" value="">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
							</div>
							<div class="col-md-10"><button type="submit" value="Publish" class="btn btn-info pull-right">Save</button>
						</div>
			</form>
		</div>
	</div>
</div>
		
	<div class="spacer"></div>

<div class="row ">
		<div class="modal-dialog col-md-12" style="width:100% !important">
       		<div class="panel panel-primary">
               	<div class="panel-heading">Review temmple
				 
               	</div>
	                <div class="panel-body" id="review-conent"></div>
			</div>
		</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('#email-temmple').keyup(function(){
		var review= $('#email-temmple').val();
		console.log(review);
		$('#review-conent').html(review).fadeIn();
	})
})
	
</script>
<style type="text/css" media="screen">
	ul.wysihtml5-toolbar {
    margin: 10px;
    padding: 0;
    display: block;
	}
</style>
@endsection
