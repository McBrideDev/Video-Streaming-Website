@extends('admincp.master')
@section('title',"Edit an Existing Pornstar's Profile")
@section ('subtitle',"Pornstar Management")
@section('content')
<div class="row ">
	<div class="modal-dialog col-md-12" style="width:100% !important">
		@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
		@if(session('msgerror'))<div class="alert alert-danger">{{session('msgerror')}}</div>@endif
		<div class="panel panel-primary">
   			<div class="panel-heading">Edit an Existing Pornstar's Profile</div>
        	<div class="panel-body">
				<form action="{{URL('admincp/edit-pornstar/')}}/{{$editpornstar->id}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<div class="form-group {{$errors->has('title_name')? 'has-error': ''}}">
						<div class="col-md-2"><label class="control-label required-field">Pornstar's Name</label></div>
						<div class="col-md-10">
							<input type="text" class="form-control" name="title_name" value="{{$editpornstar->title_name}}">
							<span class="control-label">{{$errors->first('title_name')}}</span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Gender</label></div>
						<div class="col-md-10">
							<select name="gender"  class="form-control">
								<option value="0" <?php if($editpornstar->gender==0) echo "selected=selected"; ?>>Male</option>
								<option value="1"  <?php if($editpornstar->gender==1) echo "selected=selected"; ?>>Female</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label"></label></div>
						<div class="col-md-10">
							<div class="input-group">
								<span class="input-group-addon" style="min-width: 100px" id="basic-addon1">Age</span>
								<input type="number" min="18" name="age" value="{{$editpornstar->age}}" class="form-control" aria-describedby="basic-addon1">
								<span class="input-group-addon" style="min-width: 100px"  id="basic-addon1">Born</span>
								<input type="text" name="born" value="{{$editpornstar->born}}" class="form-control" aria-describedby="basic-addon1">
								<span class="input-group-addon" style="min-width: 100px"  id="basic-addon1">Height</span>
								<input type="text" name="height" value="{{$editpornstar->height}}" class="form-control" aria-describedby="basic-addon1">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label"></label></div>
						<div class="col-md-10">
							<div class="input-group">
							  <span class="input-group-addon" style="min-width: 100px"  id="basic-addon1">Ethnicity</span>
							  <input type="text" name="ethnicity" value="{{$editpornstar->ethnicity}}" class="form-control" aria-describedby="basic-addon1">
							  <span class="input-group-addon" style="min-width: 100px"  id="basic-addon1">Hair color</span>
							  {{-- <input type="text" name="hair_color" value="{{$editpornstar->hair_color}}" class="form-control"  aria-describedby="basic-addon1"> --}}
							  <span class="{{$errors->has('hair_color')? 'has-error': ''}}">
							  	<input type="text" value="{{$editpornstar->hair_color}}" name="hair_color" class="form-control"  aria-describedby="basic-addon1">
							  </span>

							  <span class="input-group-addon" style="min-width: 100px"  id="basic-addon1">Eye color</span>
							  {{-- <input type="text" name="eye_color" value="{{$editpornstar->eye_color}}" class="form-control" aria-describedby="basic-addon1"> --}}
							  <span class="{{$errors->has('eye_color')? 'has-error': ''}}">
							  	<input type="text" value="{{$editpornstar->eye_color}}" name="eye_color" class="form-control" aria-describedby="basic-addon1">
							  </span>
							</div>
							<div class="{{$errors->has('hair_color')? 'has-error': ''}}">
								<span class="required help-block">{{$errors->first('hair_color')}}</span>
								<span class="required help-block">{{$errors->first('eye_color')}}</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Bio</label></div>
						<div class="col-md-10"><textarea class="form-control" rows="12" name="description">{{$editpornstar->description}}</textarea></div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Pornstar's Main Image</label></div>
						<div class="col-md-10">
							<span class="btn btn-info btn-file"> Select File
								<input class="form-control" id="fileSelect" type="file" name="poster" value="" placeholder="">
							</span>
							<span id="fileSelectInfo"></span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label"></label></div>
						<div class="col-md-10">
							@if($editpornstar->poster !=NULL )
							<img style="width: 120px"  src="{{URL('public/upload/pornstar/')}}/{{$editpornstar->poster}}" alt="">
							@endif
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Pornstar's Wall Image</label></div>
						<div class="col-md-10">
							<span class="btn btn-info btn-file"> Select File
								<input class="form-control" id="fileSelectWall" type="file" name="wall_poster" value="" placeholder="">
							</span>
							<span id="fileSelectInfoWall"></span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label"></label></div>
						<div class="col-md-10">
							@if($editpornstar->wall_poster !=NULL )
							<img style="max-width: 100%; height:250px"  src="{{URL('public/upload/pornstar/')}}/{{$editpornstar->wall_poster}}" alt="">
							@endif
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Status</label></div>
						<div class="col-md-10">
							<select name="status" class="form-control">
								<option value="0" <?php if($editpornstar->status==0) echo "selected=selected"; ?>>Inactive</option>
								<option value="1"  <?php if($editpornstar->status==1) echo "selected=selected"; ?>>Active</option>
							</select>
						</div>
					</div>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<center><button type="submit" value="Publish" class="btn btn-info">Save Pornstar</button></center>
				</form>
		    </div>
        </div>
    </div>
</div>

		<div class="spacer"></div>


@endsection
