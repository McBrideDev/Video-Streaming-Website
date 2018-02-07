@extends('admincp.master')
@section('title',"Edit An Tag")
@section ('subtitle',"Tag")
@section('content')
<div class="row ">
	<div class="modal-dialog col-md-12" style="width:100% !important">
			 <div class="panel panel-primary">
			<div class="panel-heading">Edit An Tag</div>
			<div class="panel-body">
				@if(session('msgerro'))<div class="alert_error">{{session('msgerro')}}</div>@endif
				<form action="{{URL('admincp/edit-tag')}}/{{$edit_page->id}}" class="form-horizontal" method="post" accept-charset="utf-8">
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Tag</label></div>
						<div class="col-md-10"><input  type="text" name="tag" class="form-control" value="{{$edit_page->tag}}" placeholder="Your tag here !"></div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label>Status</label></div>
						<div class="col-md-10">
							<select name="status" class="form-control">
								<option class="btn btn-success" value="1" <?=($edit_page->status==1)? "selected='selected'" : "" ?> >Active</option>
								<option  class="btn btn-danger" value="0" <?=($edit_page->status==0)? "selected='selected'" : "" ?> >Hidden</option>
							</select>
						</div>
					</div>
					<input type="hidden" name="id" value="">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<center><button type="submit" class="btn btn-info">Save</button></center>
				</form> 
			</div>
		</div>
	</div>
</div><!-- end of post new article -->
<div class="spacer"></div>
@endsection
