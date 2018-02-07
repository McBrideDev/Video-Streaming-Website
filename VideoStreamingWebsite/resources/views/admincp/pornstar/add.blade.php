@extends('admincp.master')
@section('title',"Add A New PornStar")
@section ('subtitle',"Pornstar Management")
@section('content')
<div class="row ">
				<div class="modal-dialog col-md-12" style="width:100% !important">
					@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
					@if(session('msgerror'))<div class="alert alert-danger">{{session('msgerror')}}</div>@endif
       				<div class="panel panel-primary">
               			<div class="panel-heading">Add A New PornStar</div>
	                		<div class="panel-body">
				<form action="{{URL('admincp/add-pornstar')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">


						<div class="form-group {{$errors->has('title_name')? 'has-error': ''}}">
							<div class="col-md-2"><label class="control-label required-field">Pornstar's Name</label></div>
							<div class="col-md-10">
								<input type="text" class="form-control" name="title_name" value="{{old('title_name')}}">
								<span class="required help-block">{{$errors->first('title_name')}}</span>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Gender</label></div>
							<div class="col-md-10">
								<select name="gender"  class="form-control">
										<option value="0">Male</option>
										<option value="1">Female</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label"></label></div>
							<div class="col-md-10">
								<div class="input-group">
								  <span class="input-group-addon" style="min-width: 100px" id="basic-addon1">Age</span>
								  <input type="number" min="18" name="age" class="form-control" aria-describedby="basic-addon1">
								  <span class="input-group-addon" style="min-width: 100px" id="basic-addon1">Born</span>
								  <input type="text"  name="born" class="form-control" aria-describedby="basic-addon1">
								  <span class="input-group-addon" style="min-width: 100px" id="basic-addon1">Height</span>
								  <input type="text" name="height" class="form-control" aria-describedby="basic-addon1">

								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label"></label></div>
							<div class="col-md-10">
								<div class="input-group">
								  <span class="input-group-addon" style="min-width: 100px" id="basic-addon1">Ethnicity</span>
								  <input type="text" name="ethnicity"  class="form-control" aria-describedby="basic-addon1">
								  <span class="input-group-addon" style="min-width: 100px" id="basic-addon1">Hair color</span>
								  <span class="{{$errors->has('hair_color')? 'has-error': ''}}">
								  	<input type="text" value="{{ old('hair_color') }}" name="hair_color" class="form-control"  aria-describedby="basic-addon1">
								  </span>
								  <span class="input-group-addon" style="min-width: 100px" id="basic-addon1">Eye color</span>
								  <span class="{{$errors->has('eye_color')? 'has-error': ''}}">
								  	<input type="text" value="{{ old('eye_color') }}" name="eye_color" class="form-control" aria-describedby="basic-addon1">
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
							<div class="col-md-10"><textarea class="form-control" rows="12" name="description"><?php if(session('description')){echo session('description'); }?></textarea></div>
						</div>
						<div class="form-group {{$errors->has('poster')? 'has-error': ''}}">
							<div class="col-md-2"><label class="control-label required-field">Pornstar's Main Image</label></div>
							<div class="col-md-10">
							<span class="btn btn-info btn-file"> Select File
								<input class="form-control" type="file" id="fileSelect" name="poster" value="" placeholder="">
							</span>
								<span id="fileSelectInfo"></span>
								<span class="required help-block">{{$errors->first('poster')}}</span>
							</div>
						</div>
						<div class="form-group {{$errors->has('wall_poster')? 'has-error': ''}}">
							<div class="col-md-2"><label class="control-label required-field">Pornstar's Wall Image</label></div>
							<div class="col-md-10">
							<span class="btn btn-info btn-file"> Select File
								<input class="form-control" type="file" id="fileSelectWall" name="wall_poster" value="" placeholder="">
							</span>
								<span id="fileSelectInfoWall"></span>
								<span class="required help-block">{{$errors->first('wall_poster')}}</span>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Status</label></div>
								<div class="col-md-10">
									<select name="status" class="form-control">
										<option value="0">Inactive</option>
										<option value="1" selected="selected">Active</option>
									</select>
								</div>
						</div>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<center><button type="submit" value="Publish" class="btn btn-info">Click Here To Add This Pornstar's Profile</button></center>


			</form>

		           </div>
           </div>
    </div>
</div>



		<div class="spacer"></div>


@endsection
