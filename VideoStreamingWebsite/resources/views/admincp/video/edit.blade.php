@extends('admincp.master')
@section('title',"Edit Existing Video's Information")
@section ('subtitle',"Video Management")
@section('content')
<div class="row ">
	<div class="modal-dialog col-md-12" style="width:100% !important">
		<div class="panel panel-primary">
			<div class="panel-heading">Edit Existing Video's Information </div>
			<div class="panel-body">
				<form action="{{URL('admincp/edit-video/')}}/{{$getvideo->string_Id}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
					<div class="form-group">
						<div class="col-md-2"><label class="label-control">Video Name</label></div>
						<div class="col-md-10"><input type="text" class="form-control" name="title_name" value="{{$getvideo->title_name}}"></div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="label-control">Post Description</label></div>
						<div class="col-md-10"><textarea rows="12" class="form-control" name="description">{{$getvideo->description}}</textarea></div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Categories</label></div> 
						<div class="col-md-10">
							<select id="categories_Id" multiple="multiple" class="form-control" name="post_result_cat[]">
								@foreach ($categories as $cate_result)
								<?php
									$data_cat=explode(',', $getvideo->cat_id);

									if(in_array($cate_result->id, $data_cat)){
										$selected="selected";
									}else{
										$selected="";
									}
								?>
								<option <?=$selected?>  data-name="{{$cate_result->title_name}}" value="{{$cate_result->id.'_'.$cate_result->title_name}}">{{$cate_result->title_name}}</option>
								@endforeach
							</select><br>
							<script type="text/javascript">$(document).ready(function(){$('#categories_Id').select2(); })</script>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Channel</label></div> 
						<div class="col-md-10">
							<select class="form-control" name="channel_Id">
								<option selected="selected">Select your Video's Channel </option>
								@foreach ($channel as $channel_result)
									<?php 
								if($getvideo->channel_Id == $channel_result ->id){
									$selected="selected";
								}else{
									 $selected="";
								}
								?>
								<option <?php echo $selected ?> value="{{$channel_result->id}}">{{$channel_result->title_name}}</option>
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
									<?php 
								if($getvideo->pornstar_Id == $pornstar_result ->id){
									$selected="selected";
								}else{
									 $selected="";
								}
								?>
								<option <?php echo $selected ?> value="{{$pornstar_result->id}}">{{$pornstar_result->title_name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="label-control">Tags</label></div> 
						<div class="col-md-10"><input type="text" class="form-control" name="tag" value="{{$getvideo->tag}}"></div>
					</div>
					
					<!-- <div class="form-group">
						<div class="col-md-2"><label class="label-control">Rating</label></div>
						<div class="col-md-10"><input type="text" name="rating" value="{{$getvideo->rating}}" ></div> 
					</div> -->
					<!-- <div class="form-group">
						<div class="col-md-2"><label class="label-control">Old Video</label></div>
						<div class="col-md-10">
								<video style="margin: auto; width: 200px; height: 120px;"  onclick="this.paused ? this.play() : this.pause();;">
									<source src="{{$getvideo->video_src}}">
								</video>
						</div> 
					</div> -->
					<!-- <div class="form-group">
						<div class="col-md-2"><label class="label-control">Upload your new video</label></div> 
						<div class="col-md-10">
							<div id="fileuploader"></div>
							
							<script type="text/javascript">
								$(document).ready(function() {
												
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
						</div>
					</div> -->
					<div class="form-group">
						<div class="col-md-2"><label class="label-control">Is this a Featured Video?</label></div> 
						<div class="col-md-3">
							<!-- <input type="checkbox" name="featured" value="" <?php if($getvideo->featured==1){echo "checked='checked'";} ?> > -->
							<select name="featured" class="form-control">
								<option value="0" <?php if($getvideo->featured==0) echo "selected=selected"; ?>>No</option>
								<option value="1"  <?php if($getvideo->featured==1) echo "selected=selected"; ?>>Yes</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="label-control">Allow comments</label></div>
						<div class="col-md-3">
							<select name="comment_status" class="form-control">
								<option value="0" <?php if($getvideo->comment_status==0) echo "selected=selected"; ?>>No</option>
								<option value="1"  <?php if($getvideo->comment_status==1) echo "selected=selected"; ?>>Yes</option>
							</select>
						</div> 
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="label-control">Click Here if this video is for sale</label></div> 
						<div class="col-md-10"><input type="checkbox" id="check_buy" name="buy_this" value="" <?php if($getvideo->buy_this==1){echo "checked='checked'";} ?> ></div>
					</div>
					<div  id="show_ppv" style="<?=($getvideo->buy_this==1)? 'display:block':'display:none' ?>">
						<div class="form-group">
							<div class="col-md-2"><label class="label-control">Form name</label></div> 
							<div class="col-md-10"><input type="text" class="form-control" name="form_name" value="{{$getvideo->form_name}}" placeholder="CCbill Form name" ></div>
						</div> 
						<div class="form-group"> 
							<div class="col-md-2"><label class="label-control">Allowed Types</label></div> 
							<div class="col-md-10"><input type="text" class="form-control" name="allowedTypes" value="{{$getvideo->allowedTypes}}" placeholder="CCbill Form allowed Types" ></div>
						</div>
						<div class="form-group">    
							<div class="col-md-2"><label class="label-control">Subscription Id</label></div> 
							<div class="col-md-10"><input type="text" class="form-control" name="subscriptionTypeId" value="{{$getvideo->subscriptionTypeId}}" placeholder="CCbill Form subscription Type Id"  ></div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><label class="control-label">Status</label></div> 
						<div class="col-md-10">
							<select name="status" class="form-control">
								<option value="0" <?php if($getvideo->status==0) echo "selected=selected"; ?>>Inactive</option>
								<option value="1"  <?php if($getvideo->status==1) echo "selected=selected"; ?>>Active</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-2"><div id="loading-upload" class="pull-left"></div></div> 
					</div>
					<hr />
					<div class="form-group">
						<input type="hidden" id="fileupload" name="fileupload" value="">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<center><button type="submit" value="Publish" class="btn btn-info">Click Here To Add This Video </button></center>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
		
<div class="spacer"></div>

<script type="text/javascript">
	$('#submit-upload').click(function (){
		$('#loading-upload').html('<img style="position: relative;top: 5px;" src="{{URL("public/assets/images/loading.gif")}}"/><label>Waiting upload...</label>').show();
	});

	$(document).ready(function(){
		$('#check_buy').click(function(){
			$('#show_ppv').slideToggle("fast");
		});
	});
</script>
@endsection
