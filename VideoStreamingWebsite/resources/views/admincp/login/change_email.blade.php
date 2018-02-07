@extends('admincp.master')
@section('title',"Change Email")
@section('content')
<div class="row ">
		<div class="modal-dialog col-md-12" >
		    @if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
			@if(session('msgerro'))<div class="alert alert-error">{{session('msgerro')}}</div>@endif
       		<div class="panel panel-primary">
               	<div class="panel-heading">Change Email</div>
	                <div class="panel-body">
						<form action="{{URL('admincp/change-email')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
							<div class="form-group">
								<div class="col-md-4"><label class="control-label">Current Email</label></div>
								<div class="col-md-8"><input class="form-control"  type="email" name="current_email"  value="{{$user}}"></div>
							</div>

							<div class="form-group {{$errors->has('new_email')? 'has-error': ''}}">
								<div class="col-md-4"><label class="control-label">New Email</label></div>
								<div class="col-md-8">
									<input class="form-control"   type="email" name="new_email" value="{{old('new_email')}}" placeholder="Enter your Email!" pla>
									<span class="required help-block">{{$errors->first('new_email')}}</span>
								</div>
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