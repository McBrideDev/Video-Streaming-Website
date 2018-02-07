@extends('admincp.master')
@section('title'," Add A New Standard Ad")
@section ('subtitle',"Advertisement Management")
@section('content')
<div class="row ">
	<div class="modal-dialog col-md-12" style="width:100% !important">
		@if(session('msg'))<div class="alert alert-success"><span class="fa fa-check"></span><strong> {{session('msg')}}</strong></div>@endif
		@if(session('msgerror'))<div class="alert alert-danger"><span class="fa fa-times"></span><strong> {{session('msgerror')}}</strong></div>@endif
		<div class="panel panel-primary">
			<div class="panel-heading">Add A New Standard Ads</div>
			<div class="panel-body">
				<form action="{{URL('admincp/add-standard-ads')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">


					<div class="form-group {{$errors->has('ads_title')? 'has-error': ''}}">
						<div class="col-md-2"><label class="control-label">Ad Name</label></div>
						<div class="col-md-10">
							<input type="text" class="form-control" name="ads_title" value="{{old('ads_title')}}">
							<span class="required help-block">{{$errors->first('ads_title')}}</span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Position</label></div>
						<div class="col-md-10">
							<select name="position" class="form-control">
								<option value="home" >Home</option>
								<option value="footer" >Footer</option>
								<option value="toprate" >Top Rate</option>
								<option value="mostview">Most View</option>
								<option value="video" >Video</option>
								<option value="pornstar" >PornStar</option>
								<option value="under_video" >Under Video Player</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Chose Ad type</label></div>
						<div class="col-md-10">
							<select id="select_type" name="type" class="form-control">
								<option value="upload" selected="selected" >Upload</option>
								<option value="script_code" >Script Code</option>
							</select>
						</div>
					</div>

					<div class="form-group {{$errors->has('script_code')? 'has-error': ''}}" id="script_code" style="display: none">
						<div class="col-md-2"><label class="control-label">Script Code</label></div>
						<div class="col-md-10">
							<textarea class="form-control" rows="5" name="script_code">{{old('script_code')}}</textarea>
							<span class="required help-block">{{$errors->first('script_code')}}</span>
						</div>
					</div>
					<div class="form-group" id="upload" style="display: none">
						<div class="col-md-2"><label class="control-label">Upload Image</label></div>
						<div class="col-md-10 {{$errors->has('ads_content')? 'has-error': ''}}">
						<span class="btn btn-info btn-file"> Select File
							<input class="form-control" type="file" id="fileSelect" name="ads_content" value="" >
						</span>
							<span id="fileSelectInfo"></span>
							<span class="required help-block">{{$errors->first('ads_content')}}</span>
						</div>
						<div class="clearfix" style="margin-bottom:10px;"></div>
						<div class="col-md-2"><label class="control-label">URL Return</label></div>
						<div class="col-md-10 {{$errors->has('return_url')? 'has-error': ''}}">
							<input class="form-control"  type="text" name="return_url" value="{{old('return_url')}}">
							<span class="required help-block">{{$errors->first('return_url')}}</span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Status</label></div>
						<div class="col-md-10">
							<select name="status" class="form-control">
								<option value="0">Inactive</option>
								<option value="1" selected="selected">Active</option>
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

<script type="text/javascript">
	$('#upload').fadeIn();
	$(document).on('change','#select_type',function(){

		if($('#select_type').val()=="upload"){
			$('#upload').fadeIn();
			$('#script_code').fadeOut();
			$('textarea[name=script_code]').val("");
		}else{
			$('#script_code').fadeIn();
			$('#upload').fadeOut();
			$('input[name=ads_content]').val("");
			$('input[name=return_url]').val("");
		}
	})
</script>



<div class="spacer"></div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#submit-upload').click(function (){
			$('#loading-upload').html('<img style="position: relative;top: 5px;"  src="{{URL("public/assets/images/loading.gif")}}"/><label>Waiting upload...</label> ').show
		})
	})
</script>

@endsection



