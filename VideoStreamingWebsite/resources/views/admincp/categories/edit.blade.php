@extends('admincp.master')
@section('title',"Edit Category")
@section('content')
<div class="row ">
				<div class="modal-dialog col-md-12" style="width:100% !important">
					@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
					@if(session('msgerro'))<div class="alert alert-error">{{session('msgerro')}}</div>@endif
       				<div class="panel panel-primary">
               			<div class="panel-heading">Edit Category</div>
	                		<div class="panel-body">
				<form action="{{URL('admincp/edit-categories/')}}/{{$editcategories->id}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				
				
						<div class="form-group required">
							<div class="col-md-2"><label class="control-label">Category Name</label></div>
							<div class="col-md-10"><input class="form-control" type="text" name="title_name" value="{{$editcategories->title_name}}"></div>
						</div>

						<!-- <div class="form-group">
							<div class="col-md-2"><label class="control-label">Post Description</label></div>
							<div class="col-md-10"><textarea class="form-control" rows="12" name="description">{{$editcategories->description}}</textarea></div>
						</div>
						
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Tags</label></div>
							<div class="col-md-10"><input class="form-control"  value="{{$editcategories->tag}}" type="text" name="tag" style="width:92%;"></div>
						</div> -->
						
						<div class="form-group"> 
							<div class="col-md-2"><label class="control-label">Recommended</label></div>
							<div class="col-md-10"><input <?=($editcategories->recomment==1)? "checked='checked'":""; ?> name="recomment" type="checkbox"></div>
						</div>
						
						<div class="form-group required">
							
							<div class="col-md-2"><label class="control-label">Category Image</label></div> 
							
							<div class="col-md-10">
								<span class="btn btn-info btn-file"> Select File
									<input class="form-control" type="file" name="poster" id="fileSelect" value="" placeholder="">
								</span>
								<span id="fileSelectInfo"></span>
							</div>
						</div>
						<div class="form-group">
							
							<div class="col-md-2"><label class="control-label"></label></div> 
							
							<div class="col-md-10">
								@if($editcategories->poster !=NULL )
								<img width="120px" src="{{URL('public/upload/categories/')}}/{{$editcategories->poster}}" alt="">
								@endif
							</div>
						</div>
						

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Status</label></div> 
								<div class="col-md-10">
									<select name="status" class="form-control">
										<option value="0" <?php if($editcategories->status==0) echo "selected=selected"; ?>>Inactive</option>
										<option value="1" <?php if($editcategories->status==1) echo "selected=selected"; ?>>Active</option>
									</select>
								</div>
						</div>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<center><button type="submit" value="Publish" class="btn btn-info">Save Category</button></center>
					
			
			</form>
		
		           </div>
           </div>
    </div>
</div>
		
		
		
		<div class="spacer"></div>
		

@endsection

