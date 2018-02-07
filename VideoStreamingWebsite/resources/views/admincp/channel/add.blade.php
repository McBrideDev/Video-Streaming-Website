@extends('admincp.master')
@section('title',"Add a New Channel ")
@section ('subtitle',"Channel Management")
@section('content')
		<div class="row ">
				<div class="modal-dialog col-md-12" style="width:100% !important">
       				<div class="panel panel-primary">
               			<div class="panel-heading">Add A New Channel</div>
	                		<div class="panel-body">
				<form action="{{URL('admincp/add-channel')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">


						<div class="form-group {{$errors->has('title_name')? 'has-error': ''}}">
							<div class="col-md-2"><label class="control-label required-field">Channel Name</label></div>
							<div class="col-md-10">
								<input type="text" class="form-control" name="title_name" value="{{old('title_name')}}">
								<span class="required help-block">{{$errors->first('title_name')}}</span>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Channel Description</label></div>
							<div class="col-md-10"><textarea class="form-control" rows="12" name="description"></textarea></div>
						</div>
						<div class="form-group {{$errors->has('poster')? 'has-error': ''}}">
							<div class="col-md-2"><label class="control-label required-field">Channel Image</label></div>
							<div class="col-md-10">
								<span class="btn btn-info btn-file"> Select File
									<input class="form-control" type="file" id="fileSelect" name="poster" value="" placeholder="">
								</span>
								<span id="fileSelectInfo"></span>
								<span class="required help-block">{{$errors->first('poster')}}</span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Allow Subscriptions</label></div>
							<div class="col-md-10"><input type="checkbox" name="subscribe_status" checked="checked" value="1"></div>
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
					<center><button type="submit" value="Publish" class="btn btn-info">Add Channel</button></center>


			</form>

		           </div>
           </div>
    </div>
</div>



		<div class="spacer"></div>


@endsection

