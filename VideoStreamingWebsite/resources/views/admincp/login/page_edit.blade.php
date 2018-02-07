@extends('admincp.master')
@section('title',"Edit An Static Page")
@section ('subtitle',"Static Page")
@section('content')
		<div class="row ">
				<div class="modal-dialog col-md-12" style="width:100% !important">
       				 <div class="panel panel-primary">
                		<div class="panel-heading">Edit An Static Page</div>
	                    <div class="panel-body">
	                    	@if(session('msgerro'))<div class="alert_error">{{session('msgerro')}}</div>@endif
	                        <form action="{{URL('admincp/edit-static-page')}}/{{$edit_page->id}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
								<div class="form-group">
									<div class="col-md-2"><label class="control-label">Title Page</label></div>
									<div class="col-md-10"><input  type="text" name="titlename" class="form-control" value="{{$edit_page->titlename}}" placeholder="Your title page here !"></div>
								</div>
								<div class="form-group">
									<div class="col-md-2"><label>Content</label></div>
									<div class="col-md-10"><textarea name="content_page" required rows="15" name="description" class=" form-control wysiwyg">{{$edit_page->content_page}}</textarea></div>
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