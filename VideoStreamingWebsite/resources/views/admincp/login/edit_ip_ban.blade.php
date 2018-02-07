@extends('admincp.master')
@section('title',"Ban IP")
@section('content')
<div class="row ">
		<div class="modal-dialog col-md-12" >
		    @if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
			@if(session('msgerro'))<div class="alert alert-error">{{session('msgerro')}}</div>@endif
       		<div class="panel panel-primary">
               	<div class="panel-heading">Add IP Ban</div>
	                <div class="panel-body">
						<form action="{{URL('admincp/edit-banip')}}/{{$edit_ip->id}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
							<div class="form-group">
								<div class="col-md-4"><label class="control-label">Ip Ban</label></div>
								<div class="col-md-8"><input class="form-control"  type="text" name="ip_ban"  value="{{$edit_ip->ip_ban}}" placeholder="xxx.xxx.xxx.xxx"></div>
							</div>
							<div class="form-group">
							<div class="col-md-4"><label class="control-label">Status</label></div> 
								<div class="col-md-8">
									<select name="status" class="form-control">
										<option value="0" <?php if($edit_ip->status==0) echo "selected=selected"; ?>>Open</option>
										<option value="1" <?php if($edit_ip->status==1) echo "selected=selected"; ?>>Block</option>
									</select>
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