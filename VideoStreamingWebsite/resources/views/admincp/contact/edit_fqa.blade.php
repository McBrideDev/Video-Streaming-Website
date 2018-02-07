@extends('admincp.master')
@section('title',"Add FQA")
@section ('subtitle',"Administrators")
@section('content')
		<div class="row ">
				<div class="modal-dialog col-md-12" style="width:100% !important">
       				<div class="panel panel-primary">
               			<div class="panel-heading">Edit FAQ</div>
	                		<div class="panel-body">
				<form action="{{URL('admincp/edit-faq')}}/{{$edit->id}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Question</label></div>
							<div class="col-md-10">
								<textarea class="form-control" rows="5" name="question" >{{$edit->question}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Answer</label></div>
							<div class="col-md-10">
								<textarea class="form-control" rows="5" name="answer" >{{$edit->answer}}</textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Status</label></div> 
								<div class="col-md-10">
									<select name="status" class="form-control">
										<option value="0" <?=($edit->status==0)? 'selected="selected"':''  ?>>Inactive</option>
										<option value="1" <?=($edit->status==1)? 'selected="selected"':''  ?>>Active</option>
									</select>
								</div>
						</div>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<button type="submit" value="Publish" class="btn btn-info pull-right">Save</button>
					
			
			</form>
		
		           </div>
           </div>
    </div>
</div>
		
		
		
		<div class="spacer"></div>
		

@endsection

