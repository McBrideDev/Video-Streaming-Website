@extends('admincp.master')
@section('title',"Ban An IP Address")
@section ('subtitle',"User Management")
@section('content')
<div class="row ">
		<div class="modal-dialog col-md-12" >
		    @if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
			@if(session('msgerror'))<div class="alert alert-danger">{{session('msgerror')}}</div>@endif
       		<div class="panel panel-primary">
               	<div class="panel-heading">Ban an IP Address</div>
	                <div class="panel-body">
						<form action="{{URL('admincp/add-banip')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
							<div class="form-group">
								<div class="col-md-4"><label class="control-label">Ban the following IP Address</label></div>
								<div class="col-md-8"><input class="form-control"  type="text" name="ip_ban"  value="" placeholder="xxx.xxx.xxx.xxx"></div>
							</div>
							<!-- <div class="form-group">
							<div class="col-md-4"><label class="control-label">Status</label></div> 
								<div class="col-md-8">
									<select name="status" class="form-control">
										<option value="0" >Open</option>
										<option value="1" >Block</option>
									</select>
								</div>
							</div> -->
							<div class="form-group">
								<input type="hidden" name="id" value="">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<center><button type="submit" value="Publish" class="btn btn-info">Update</button></center>
							</div>
	
						</form>
					</div>
				</div> 
			</div>
		</div>
	</div>
		<div class="spacer"></div>
		

@endsection