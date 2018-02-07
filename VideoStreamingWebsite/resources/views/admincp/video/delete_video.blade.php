@extends('admincp.master')
@section('title',"Delete Video CSV")
@section ('subtitle',"Delete Video")
@section('content')
<div class="row">
	<div class="col-md-12">
		<div id="home" class="tab-pane fade in active">
			<div class="modal-dialog col-md-12" style="width:100% !important">
				@if(session('msg'))
					{!!session('msg')!!}
				@endif
				<div class="panel panel-primary">
					<div class="panel-heading">Delete Video CSV</div>
					<div class="panel-body">
						<form action="{{URL('admincp/delete-video-csv')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
							<div class="form-group">
								<div class="col-md-2"><label class="label-control required-field">Choose file</label></div>
								<div class="col-md-10">
									<div id="fileuploader"></div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-2"><label class="label-control">Website</label></div>
								<div class="col-md-10">
									<select id="selectWebsite" name="website" class="form-control">
										<option value="www.pornhub.com">www.pornhub.com</option>
										<option value="www.redtube.com">www.redtube.com</option>
										<option value="www.tube8.com">www.tube8.com</option>
										<option value="www.youporn.com">www.youporn.com</option>
										<option value="www.xtube.com">www.xtube.com</option>
										@foreach($tubeWeb as $tube)
											<option value="{{$tube}}">www.{{$tube}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<center>
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							<input type="hidden" name="string_id" value="<?php echo uniqid(); ?>">
							<input type="hidden" name="file" id="file" value="">
							<button type="submit" name="delete"  class="btn btn-info">Delete</button>
							</center>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#fileuploader").uploadFile({
			url:"{{URL('admincp/auto-upload-csv')}}",
			fileName:"myfile",
			allowedTypes:"csv",
			formData: [{ name: '_token', value: $('meta[name="csrf-token"]').attr('content') },{ name: 'string_id', value: $('input[name="string_id"]').attr('value') }],
			multiple: false,
			autoSubmit:true,
			onSuccess:function(files,data,xhr) {
				$('#file').val(files);
			}
		});
	});
</script>

@endsection
