@extends('admincp.master')
@section('title',"Add A New Video Ad")
@section ('subtitle',"Advertisement Management")
@section('content')
<div class="row ">
		<div class="modal-dialog col-md-12" style="width:100% !important">
		    @if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
			@if(session('msgerror'))<div class="alert alert-danger">{{session('msgerror')}}</div>@endif
       		<div class="panel panel-primary">
               	<div class="panel-heading">Add a New Video Ad</div>
	                <div class="panel-body">
				<form action="{{URL('admincp/in-player-media-ads')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">


						<div class="form-group {{$errors->has('title')? 'has-error': ''}}">
							<div class="col-md-2"><label class="control-label">Ad Name</label></div>
							<div class="col-md-10">
								<input class="form-control" type="text" name="title" value="{{old('title')}}" placeholder="">
							 	<span class="required help-block">{{$errors->first('title')}}</span>
							</div>
						</div>


						<div class="form-group {{$errors->has('descr')? 'has-error': ''}}">
							<div class="col-md-2"><label class="control-label">Description</label></div>
							<div class="col-md-10">
								<input class="form-control" type="text" name="descr" value="{{old('descr')}}" placeholder="">
								<span class="required help-block">{{$errors->first('descr')}}</span>
							</div>
						</div>

						<div class="form-group {{$errors->has('adv_url')? 'has-error': ''}}">
							<div class="col-md-2"><label class="control-label">Ad URL</label></div>
							<div class="col-md-10">
								<input class="form-control" type="text" name="adv_url" value="{{old('adv_url')}}" placeholder="">
								<span class="required help-block">{{$errors->first('adv_url')}}</span>
							</div>
						</div>

						<div class="form-group {{$errors->has('media')? 'has-error': ''}}">
							<div class="col-md-2"><label class="control-label">Upload Media File</label></div>
							<div class="col-md-10">
								<input  class="form-control" type="file" name="media" ><small>(*.mp4)</small>
								<span class="required help-block">{{$errors->first('media')}}</span>
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
