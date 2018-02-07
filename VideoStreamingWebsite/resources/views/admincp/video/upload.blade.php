@extends('admincp.master')
@section('title',"Add A New Video")
@section ('subtitle',"Video Management")
@section('content')
<div class="row ">
	<div class="modal-dialog col-md-12" style="width:100% !important">
		<div class="panel panel-primary">
			<div class="panel-heading">Add A New Video</div>
			<div class="panel-body">
				<form action="{{URL('admincp/add-video')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<div class="form-group {{$errors->has('title_name')? 'has-error': ''}}">
						<div class="col-md-2"><label class="label-control required-field">Video Title</label></div>
						<div class="col-md-10">
							<input type="text" class="form-control" name="title_name" value="{{old('title_name')}}">
							<span class="required help-block">{{$errors->first('title_name')}}</span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="label-control">Video Description</label></div>
						<div class="col-md-10"><textarea rows="12" class="form-control" name="description"></textarea></div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Categories</label></div>
						<div class="col-md-10">
							<select id="categories_Id"   multiple="multiple" class="form-control"name="post_result_cat[]">
								@foreach ($categories as $cate_result)
								<option  data-name="{{$cate_result->title_name}}" value="{{$cate_result->id}}_{{$cate_result->title_name}}">{{$cate_result->title_name}}</option>
								@endforeach
							</select><br>
							<!-- <div id="add-cat-msg"></div>
							<input id="categories_result" class="form-control" readonly="readonly" type="text" name="categories_result" value="" >
							<input type="hidden" id="post_result_cat" name="post_result_cat" value=""> -->
							<script type="text/javascript">$(document).ready(function(){$('#categories_Id').select2(); })

							</script>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Channel</label></div>
						<div class="col-md-10">
							<select class="form-control" name="channel_Id">
								<option selected="selected">Select Video's Associated Channel</option>
								@foreach ($channel as $channel_result)
								<option value="{{$channel_result->id}}">{{$channel_result->title_name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Pornstar</label></div>
						<div class="col-md-10">
							<select class="form-control" name="pornstar_Id">
								<option selected="selected">Select Associated Pornstar</option>
								@foreach ($pornstar as $pornstar_result)
								<option value="{{$pornstar_result->id}}">{{$pornstar_result->title_name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="label-control">Tags</label></div>
						<div class="col-md-10"><input type="text" class="form-control" name="tag" value="" ></div>
					</div>

					<!-- <div class="form-group">
						<div class="col-md-2"><label class="label-control">Rating</label></div>
						<div class="col-md-10"><input type="text" name="rating" value="5" ></div>
					</div> -->
					<div class="form-group {{$errors->has('fileupload')? 'has-error': ''}}">
						<div class="col-md-2"><label class="label-control required-field">Upload your new video</label></div>
						<div class="col-md-10">
							<!-- <input class="form-control" type="file" name="videofile" value="" /> -->
							<div id="fileuploader"></div>

							<script type="text/javascript">
								$(document).ready(function() {
									$.ajaxSetup({
										headers: {
											'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
										}
									});

									$("#fileuploader").uploadFile({
										url:"{{URL('admincp/auto-upload-video')}}",
										fileName:"myfile",
										allowedTypes:"mp4,mov,avi,flv",
										formData: [{ name: '_token', value: $('meta[name="csrf-token"]').attr('content') },{ name: 'string_id', value: $('input[name="string_id"]').attr('value') }],
										multiple: false,
										autoSubmit:true,
										onSuccess:function(files,data,xhr)
										{
											$('#fileupload').val(files);
										}
									});
								});
							</script>
							<span class="required help-block">{{$errors->first('fileupload')}}</span>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="label-control">Is this a Featured Video?</label></div>
						<div class="col-md-3">
							<select name="featured" class="form-control">
								<option value="0" >No</option>
								<option value="1" >Yes</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="label-control">Allow comments</label></div>
						<div class="col-md-3">
							<select name="comment_status" class="form-control">
								<option value="0" >No</option>
								<option selected value="1" >Yes</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="label-control">Click Here if this video is for sale</label></div>
						<div class="col-md-10"><input id="check_buy" type="checkbox" name="buy_this" value="" ></div>
						<div class="clearfix"></div>
					</div>
					<div  id="show_ppv" style="display: none">
						<div class="form-group">
							<div class="col-md-2"><label class="label-control">Form name</label></div>
							<div class="col-md-10"><input type="text" class="form-control" name="form_name" value=""  placeholder="CCbill Form name" ></div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="label-control">Allowed Types</label></div>
							<div class="col-md-10"><input type="text" class="form-control" name="allowedTypes" value=""  placeholder="CCbill Form allowed Types"></div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="label-control">Subscription Id</label></div>
							<div class="col-md-10"><input type="text" class="form-control" name="subscriptionTypeId" value="" placeholder="CCbill Form subscription Type Id"></div>
						</div>
					</div>
					<!-- <div class="form-group">
						<div class="col-md-2"><label class="control-label">Status</label></div>
							<div class="col-md-10">
								<select name="status" class="form-control">
									<option value="0">Inactive</option>
									<option value="1">Active</option>
								</select>
							</div>
					</div> -->
					<div class="form-group">
						<div class="col-md-2"><div id="loading-upload" class="pull-left"></div></div>
					</div>
					<input type="hidden" id="file_hidden" name="file_hidden" >
					<input type="hidden" name="string_id" id="string_id" value="<?=mt_rand()?>">
					<input type="hidden" id="fileupload" name="fileupload" >
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<center><button type="submit" id="submit-upload" value="Publish" class="btn btn-info">Save</button></center>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="spacer"></div>

<script type="text/javascript">
//     $(function() {
//     $('#file_upload').uploadify({
//         'multi'    : false,
//         'auto'     : true,
//         'checkExisting' : '{{URL("public/assets/misc/check-exists.php")}}',
//         'swf': '{{URL("public/assets/misc/uploadify.swf")}}',
//         'uploader' : '{{URL("public/assets/misc/uploadify.php")}}',
//         'uploadLimit' : 1,
//         'onSelect' : function(file) {
//            document.getElementById("file_hidden").value=file.name;
//            $('#submit-upload').css('display','none');

//         },'onUploadSuccess': function(file){
//             $('#submit-upload').css('display','block');
//         }
//     });
// });
	$('#submit-upload').click(function (){
		$('#jax-loading').modal('show');
	})
	$(document).ready(function(){
		$('#check_buy').click(function(){
			$('#show_ppv').slideToggle("fast");
		})
	})
</script>
@endsection
