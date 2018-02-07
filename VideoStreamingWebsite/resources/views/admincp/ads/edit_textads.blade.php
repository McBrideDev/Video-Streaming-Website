@extends('admincp.master')
@section('title',"Edit an Existing Text Ad")
@section ('subtitle',"Advertisement Management")
@section('content')
<div class="row ">
		<div class="modal-dialog col-md-12" style="width:100% !important">
		    @if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
			@if(session('msgerro'))<div class="alert alert-danger">{{session('msgerro')}}</div>@endif
       		<div class="panel panel-primary">
               	<div class="panel-heading">Edit an Existing Text Ad</div>
	                <div class="panel-body">
				<form action="{{URL('admincp/edit-video-text-ads&is='.$editads->id)}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				
			
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Ad Title</label></div>
							<div class="col-md-10"><input class="form-control" type="text" name="ads_title" value="{{$editads->ads_title}}" placeholder=""></div>
						</div>
						

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Ad Description </label></div>
							<div class="col-md-10"><input class="form-control" type="text" name="ads_content" value="{{$editads->ads_content}}" placeholder=""></div>
						</div>

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Ad URL </label></div>
							<div class="col-md-10"><input class="form-control" type="text" name="return_url" value="{{$editads->return_url}}" placeholder=""></div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Status</label></div>
							<div class="col-md-10">
								<select class="form-control" name="status">
									<option value="1" <?=($editads->status==1)? 'selected="selected"':'' ?> >Active</option>
									<option value="0" <?=($editads->status==0)? 'selected="selected"':'' ?>>Inactive</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2">
								<input type="hidden" name="id" value="">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
							</div>
							
						</div>
						<div class="form-group">
							<center><button type="submit" value="Publish" class="btn btn-info">Save</button></center>
						</div>

			</form>
		</article><!-- end of post new article -->
		
		
		
		<div class="spacer"></div>
		

@endsection
