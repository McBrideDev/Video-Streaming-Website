@extends('admincp.master')
@section('title',"Edit Channel ")
@section ('subtitle',"Channel Management")
@section('content')
<div class="row ">
				<div class="modal-dialog col-md-12" style="width:100% !important">
					@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
					@if(session('msgerro'))<div class="alert alert-error">{{session('msgerro')}}</div>@endif
       				<div class="panel panel-primary">
               			<div class="panel-heading">Edit Channel</div>
	                		<div class="panel-body">
				<form action="{{URL('admincp/edit-channel/')}}/{{$editchannel->id}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">


						<div class="form-group {{$errors->has('title_name')? 'has-error': ''}}">
							<div class="col-md-2"><label class="control-label required-field">Channel Name</label></div>
							<div class="col-md-10">
								<input type="text" class="form-control" name="title_name" value="{{$editchannel->title_name}}">
								<span class="required help-block">{{$errors->first('title_name')}}</span>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Channel Description</label></div>
							<div class="col-md-10"><textarea class="form-control" rows="12" name="description">{{$editchannel->description}}</textarea></div>
						</div>
						<div class="form-group ">
							<div class="col-md-2"><label class="control-label required-field">Channel Image</label></div>
							<div class="col-md-10">
							<span class="btn btn-info btn-file"> Select File
								<input class="form-control" type="file" id="fileSelect" name="poster" value="" placeholder="">
							</span>
							<span id="fileSelectInfo"></span>
							{{-- <span class="required help-block">{{$errors->first('poster')}}</span> --}}
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label"></label></div>
							<div class="col-md-10">
								@if($editchannel->poster !=NULL )
								<img style="width: 120px"  src="{{URL('public/upload/channel/')}}/{{$editchannel->poster}}" alt="">
								@endif
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Allow Subscriptions</label></div>
							<div class="col-md-10"><input type="checkbox" <?php if($editchannel->subscribe_status==1) echo "checked=checked"; ?> name="subscribe_status" ></div>
						</div>

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Status</label></div>
								<div class="col-md-10">
									<select name="status" class="form-control">
										<option value="0" <?php if($editchannel->status==0) echo "selected=selected"; ?>>Inactive</option>
										<option value="1"  <?php if($editchannel->status==1) echo "selected=selected"; ?>>Active</option>
									</select>
								</div>
						</div>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<center><button type="submit" value="Publish" class="btn btn-info">Save Channel</button></center>


			</form>

		           </div>
           </div>
    </div>
</div>



		<div class="spacer"></div>


@endsection
