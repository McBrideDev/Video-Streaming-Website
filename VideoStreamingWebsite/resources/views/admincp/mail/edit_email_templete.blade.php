@extends('admincp.master')
@section('title',"Edit An Existing Email Template ")
@section ('subtitle',"Settings")
@section('content')
<div class="row ">
		<div class="modal-dialog col-md-12" style="width:100% !important">
		    @if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
			@if(session('msgerro'))<div class="alert alert-danger">{{session('msgerro')}}</div>@endif
       		<div class="panel panel-primary">
               	<div class="panel-heading">Edit An Existing Email Template</div>
	                <div class="panel-body">
				<form action="{{URL('admincp/edit-email-templete&id=')}}{{$edit_temp->id}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				
			
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Name</label></div>
							<div class="col-md-10"><input class="form-control" type="text" name="name" value="{{$edit_temp->name}}" placeholder=""></div>
						</div>
						

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Description</label></div>
							<div class="col-md-10">
								<input class="form-control" type="text" name="description" value="{{$edit_temp->description}}" placeholder="">
								
							</div>
						</div>
						
						<div class="form-group">
							
							<div class="col-md-2"><label class="control-label">Content</label></div>
							<div class="col-md-10">
								<span>Global element: <span class="label label-info"> First name: {{$firstname}}</span>,<span class="label label-info">Last name: {{$lastname}}</span>,<span class="label label-info">New password: {{$newpassword}}</span>,<span class="label label-info">Token: {{$token}}</span>,<span class="label label-info">Email: {{$email}}</span>,<span class="label label-info">Content: {{$content}}</span>,<span class="label label-info"> Site name: {{$site_name}}</span>,<span class="label label-info"> Site email: {{$site_email}}</span>,<span class="label label-info"> Site Phone: {{$site_phone}}</span>,<span class="label label-info"> Channel name: {{$channel_name}}</span></span>
								<textarea name="content" rows="15" id="email-temmple" class="form-control wysiwyg">{{$edit_temp->content}}</textarea>
							</div>

						</div>
						<!-- <div class="form-group">
							<div class="col-md-2"><label class="control-label">Status</label></div>
							<div class="col-md-10">
								<select class="form-control" name="status">
									<option value="1" <?=($edit_temp->status==1)? 'selected="selected"':'' ?> >Active</option>
									<option value="0" <?=($edit_temp->status==0)? 'selected="selected"':'' ?> >Block</option>
								</select>
							</div>
						</div> -->
						<div class="form-group">
								<input type="hidden" name="id" value="{{$edit_temp->id}}">
								<input type="hidden" name="name_slug" value="{{$edit_temp->name_slug}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
					
							<center><button type="submit" value="Publish" class="btn btn-info">Save</button></center>
						</div>

			</form>
		</div>
	</div>
</div>
		
		
		<div class="spacer"></div>

<div class="row ">
		<div class="modal-dialog col-md-12" style="width:100% !important">
       		<div class="panel panel-primary">
               	<div class="panel-heading">Review temmple</div>
	                <div class="panel-body" id="review-conent"><?=$edit_temp->content?></div>
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
