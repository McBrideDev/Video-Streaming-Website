@extends('admincp.master')
@section('title',"Add FQA")
@section ('subtitle',"Administrators")
@section('content')
<div class="row ">
	<div class="modal-dialog col-md-12" style="width:100% !important"> 
		<div class="panel panel-primary">
			<div class="panel-heading">Add New FAQ</div>
			<div class="panel-body">
				<form action="{{URL('admincp/add-faq')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<div class="form-group {{$errors->has('question')? 'has-error': ''}}">
						<div class="col-md-2"><label class="control-label">Question</label></div>
						<div class="col-md-10">
							<textarea class="form-control" rows="5" name="question" >{{Input::old('question')}}</textarea>
							<span class="required help-block">{{$errors->first('question')}}</span>
						</div>
					</div>
					<div class="form-group {{$errors->has('answer')? 'has-error': ''}}">
						<div class="col-md-2"><label class="control-label">Answer</label></div>
						<div class="col-md-10">
							<textarea class="form-control" rows="5" name="answer" >{{Input::old('answer')}}</textarea>
							<span class="required help-block">{{$errors->first('answer')}}</span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Status</label></div> 
						<div class="col-md-10">
							<select name="status" class="form-control">
								<option value="0">Inactive</option>
								<option selected value="1" >Active</option>
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

