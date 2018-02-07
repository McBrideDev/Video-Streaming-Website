@extends('admincp.master')
@section('title',"Theme setting")
@section ('subtitle',"Settings")
@section('content')
<div class="row ">
	<div class="modal-dialog col-md-12" style="width:100% !important">
		@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
		@if(session('msgerror'))<div class="alert alert-danger">{{session('msgerror')}}</div>@endif
		<div class="panel panel-primary">
			<div class="panel-heading">Theme Setting</div>
			<div class="panel-body">
				<form action="{{URL('admincp/theme-setting')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">					
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Site Theme </label></div>
						<div class="col-md-10">
							<select name="theme" class="form-control">
								<option value="dark" <?=($option->site_theme =='dark')? 'selected="selected"':''?> >Dark Theme</option>
								<option value="light" <?=($option->site_theme =='light')? 'selected="selected"':''?> >Light Theme</option>
							</select>							
						</div>						
					</div>					

					<div class="form-group">
							<input type="hidden" name="id" value="{{$option->id}}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<center><button type="submit" value="Publish" class="btn btn-info">Save</button></center>
					</div>
				</form>
			</div>
		</div>
		<div class="spacer"></div>
	</div>
</div>
@endsection
