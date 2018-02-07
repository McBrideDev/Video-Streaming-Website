@extends('admincp.master')
@section('title',"Change Password")
@section('content')
<div class="row ">
		<div class="modal-dialog col-md-12" >
		    @if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
			@if(session('msgerro'))<div class="alert alert-error">{{session('msgerro')}}</div>@endif
       		<div class="panel panel-primary">
               	<div class="panel-heading">Change Password</div>
	                <div class="panel-body">
						<form action="{{URL('admincp/change-password')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
							<div class="form-group">
								<div class="col-md-4"><label class="control-label">Current Password</label></div>
								<div class="col-md-8"><input class="form-control"  type="password" name="current_pass"  value="" placeholder="************************"></div>
							</div>

							<div class="form-group">
								<div class="col-md-4"><label class="control-label">New Password</label></div>
								<div class="col-md-8"><input class="form-control"   type="password" name="new_pass" value="" placehsolder="************************"></div>
							</div>
							<div class="form-group">
								<div class="col-md-2"><input type="hidden" name="id" value="">
									<input type="hidden" name="_token" value="{{ csrf_token() }}">
								</div>

								<div class="col-md-10"><button type="submit" value="Publish" class="btn btn-info pull-right">Update</button></div>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
		<div class="spacer"></div>


@endsection