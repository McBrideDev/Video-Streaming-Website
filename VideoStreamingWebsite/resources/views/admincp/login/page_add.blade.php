@extends('admincp.master')
@section('title',"Static Page")
@section('content')
		<div class="row ">
				<div class="modal-dialog col-md-12" style="width:100% !important">
       				 <div class="panel panel-primary">
                		<div class="panel-heading">Static Page</div>
	                    <div class="panel-body">
	                    	@if(session('msgerro'))<div class="alert_error">{{session('msgerro')}}</div>@endif
	                        <form action="{{URL('admincp/add-static-page')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
								<div class="form-group">
									<div class="col-md-2"><label class="control-label">Title Page</label></div>
									<div class="col-md-10"><input  type="text" name="titlename" class="form-control" value="" placeholder="Your title page here !"></div>
								</div>
								<div class="form-group">
									<div class="col-md-2"><label>Content</label></div>
									<div class="col-md-10"><textarea name="content_page" required rows="15" name="description" class=" form-control wysiwyg"></textarea></div>
								</div>
						
								<input type="hidden" name="id" value="">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<button type="submit" class="btn btn-info pull-right">Save</button>
						

							</form> 
	                    </div>
            		</div>
        		</div>
		</div><!-- end of post new article -->
		<div class="spacer"></div>
		

@endsection