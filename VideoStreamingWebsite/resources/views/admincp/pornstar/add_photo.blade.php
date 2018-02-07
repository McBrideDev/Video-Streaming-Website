@extends('admincp.master')
@section('title',"Upload an Image to ".$pornstar_photo->title_name."’s Photo Gallery")
@section ('subtitle',"Pornstar Management")
@section('content')
<div class="row ">
				<div class="modal-dialog col-md-12" style="width:100% !important">
					@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
					@if(session('msgerror'))<div class="alert alert-danger">{{session('msgerror')}}</div>@endif
       				<div class="panel panel-primary">
               			<div class="panel-heading">Add an image to {{$pornstar_photo->title_name}}’s Photo Gallery</div>
	                		<div class="panel-body">
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Upload Image</label></div> 
						<div class="col-md-10">
							<div id="pornstar_album"></div>

							<script type="text/javascript">
								$(document).ready(function() {

									$("#pornstar_album").uploadFile({
										url:"{{URL('admincp/pornstar-upload-allbum')}}",
										fileName:"myfile",
										allowedTypes:"PNG,png,JPG,jpg,GIF,gif",
										formData: [{ name: '_token', value: $('meta[name="csrf-token"]').attr('content') },{ name: 'porn_id', value: $('input[name="porn_id"]').attr('value') }],
										multiple: true,
										sequential:true,
										sequentialCount:1,
										maxFileCount:10,
										autoSubmit:true,
										onSuccess:function(files,data,xhr)
										{
											$('#fileupload').val(files);
										}
									});
								});

							</script>
						</div>
					</div>
					<input type="hidden" name="porn_id" value="{{$porn_id}}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<center><a href="{{URL('admincp/photo-pornstar/')}}/{{$porn_id}}"><button type="" class="btn btn-info" >Back</button></a></center>
		
		           </div>
           </div>
    </div>
</div>
		
		
		
		<div class="spacer"></div>
		

@endsection
