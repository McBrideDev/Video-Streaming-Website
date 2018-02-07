@extends('admincp.master')
@section('title',"Add A New Text Ad")
@section ('subtitle',"Advertisement Management")
@section('content')
<div class="row ">
		<div class="modal-dialog col-md-12" style="width:100% !important">
		    @if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
			@if(session('msgerro'))<div class="alert alert-danger">{{session('msgerro')}}</div>@endif
       		<div class="panel panel-primary">
               	<div class="panel-heading">Add A New Text Ad</div>
	                <div class="panel-body">
				<form action="{{URL('admincp/add-video-text-ads')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				
			
						<div class="form-group {{$errors->has('ads_title')? 'has-error': ''}}">
							<div class="col-md-2"><label class="control-label">Name</label></div>
							<div class="col-md-10">
								<input class="form-control" type="text" name="ads_title" value="{{old('ads_title')}}" placeholder="">
								<span class="required help-block">{{$errors->first('ads_title')}}</span>
							</div>
						</div>
						

						<div class="form-group {{$errors->has('ads_content')? 'has-error': ''}}">
							<div class="col-md-2"><label class="control-label">Description</label></div>
							<div class="col-md-10">
								<input class="form-control" type="text" name="ads_content" value="{{old('ads_content')}}" placeholder="">
								<span class="required help-block">{{$errors->first('ads_content')}}</span>
							</div>
						</div>

						<div class="form-group {{$errors->has('return_url')? 'has-error': ''}} ">
							<div class="col-md-2"><label class="control-label">Ad URL</label></div>
							<div class="col-md-10">
								<input class="form-control" type="text" name="return_url" value="{{old('return_url')}}" placeholder="">
								<span class="required help-block">{{$errors->first('return_url')}}</span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Status</label></div>
							<div class="col-md-10">
								<select class="form-control" name="status">
									<option value="1">Active</option>
									<option value="0">Inactive</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							
								<input type="hidden" name="id" value="">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
							
							<center><button type="submit" value="Publish" class="btn btn-info">Save</button></center>
						</div>

			</form>
		</article><!-- end of post new article -->
		
		
		
		<div class="spacer"></div>
		

@endsection
