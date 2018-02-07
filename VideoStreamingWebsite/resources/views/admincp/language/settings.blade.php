@extends('admincp.master')
@section('title',"Language Settings")
@section ('subtitle',"Language Management")
@section('content')
<div class="row ">
	<div class="modal-dialog col-md-12" style="width:100% !important">
		@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
		@if(session('msgerror'))<div class="alert alert-danger">{{session('msgerror')}}</div>@endif
		<div class="panel panel-primary">
			<div class="panel-heading">Language Settings</div>
			<div class="panel-body">
				<form action="{{URL('admincp/language/setting')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Enable Multi Language</label></div>
						<div class="col-md-10">
							<select name="isLanguage" class="form-control">
								<option value="active" <?=($l_setting->isLanguage== 'active')? 'selected="selected"':''  ?> >Enable</option>
								<option value="inactive" <?=($l_setting->isLanguage=='inactive')? 'selected="selected"':''  ?> >Disable</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Default language</label></div>
						<div class="col-md-10">
							<select name="defaultLanguage" class="form-control">
							@foreach($languageList as $result)
								<option {!!$l_setting->defaultLanguage === $result->id ? 'selected': '' !!} value="{{$result->id}}"  >{{$result->languageName}}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<input type="hidden" name="id" value="{{$l_setting->id}}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<center><button type="submit" value="Publish" class="btn btn-info">Save</button></center>
					</div>
				</form>
			</article><!-- end of post new article -->
<div class="spacer"></div>
@endsection