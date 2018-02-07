@extends('admincp.master')
@section('title',"Edit Standard Ads")
@section ('subtitle',"Advertisement Management")
@section('content')

<div class="row ">
				<div class="modal-dialog col-md-12" style="width:100% !important">
					@if(session('msg'))<div class="alert alert-success"><span class="fa fa-check"></span><strong> {{session('msg')}}</strong></div>@endif
					@if(session('msgerror'))<div class="alert alert-danger"><span class="fa fa-times"></span><strong> {{session('msgerror')}}</strong></div>@endif
       				<div class="panel panel-primary">
               			<div class="panel-heading">Edit An Existing Standard Ad</div>
	                		<div class="panel-body">
				<form action="{{URL('admincp/edit-standard-ads&is=')}}{{$editstandard->id}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				
				
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Ad Name</label></div>
							<div class="col-md-10"><input type="text" class="form-control" name="ads_title" value="{{$editstandard->ads_title}}"></div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Position</label></div>
							<div class="col-md-10">
								<select name="position" class="form-control">
									<option value="home" <?= ($editstandard->position=="home")? "selected='selected'":"" ?> >Home</option>
									<option value="footer" <?= ($editstandard->position=="footer")? "selected='selected'":"" ?> >Footer</option>
									<option value="toprate" <?= ($editstandard->position=="toprate")? "selected='selected'":"" ?> >Top Rate</option>
									<option value="mostview" <?= ($editstandard->position=="mostview")? "selected='selected'":"" ?> >Most View</option>
									<option value="video" <?= ($editstandard->position=="video")? "selected='selected'":"" ?> >Video</option>
									<option value="pornstar" <?= ($editstandard->position=="pornstar")? "selected='selected'":"" ?> >PornStar</option>
									<option value="under_video" <?= ($editstandard->position=="under_video")? "selected='selected'":"" ?> >Under Video Player</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Chose An Ad Type</label></div>
							<div class="col-md-10">
								<select id="select_type" name="type" class="form-control">
									<option >Select type ad</option>
									<option value="upload" <?= ($editstandard->type=="upload")? "selected='selected'":"" ?> >Upload</option>
									<option value="script_code" <?= ($editstandard->type=="script_code")? "selected='selected'":"" ?> >Script Code</option>
								</select>
							</div>
						</div>
						
						<div class="form-group" id="script_code" style="<?=($editstandard->type=="script_code")? "display:block":"display:none" ?>" >
							<div class="col-md-2"><label class="control-label">Script Code</label></div>
							<div class="col-md-10"><textarea class="form-control" rows="5" name="script_code">{{$editstandard->script_code}} </textarea></div>
						</div>
						

						<div class="form-group" id="upload" style="<?=($editstandard->type=="upload")? "display:block":"display:none" ?>" >
							<div class="col-md-2"><label class="control-label">Upload Image</label></div>
							<div class="col-md-10">
								<span class="btn btn-info btn-file"> Select File
									<input class="form-control" type="file" id="fileSelect" name="ads_content" value="" >
								</span>
								 <span id="fileSelectInfo"></span>
							</div>
							<div class="clearfix" style="margin-bottom:10px;"></div>
							<div class="col-md-2"><label class="control-label"></label></div>
							<div class="col-md-10"><img src="{{$editstandard->ads_content}}" width="100" alt=""></div>
							<div class="clearfix" style="margin-bottom:10px;"></div>
							<div class="col-md-2"><label class="control-label">This Ad Points To</label></div>
							<div class="col-md-10"><input class="form-control"  type="text" name="return_url" value="{{$editstandard->return_url}}"></div>
						</div>
						
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Status</label></div> 
								<div class="col-md-10">
									<select name="status" class="form-control">
										<option value="0" <?= ($editstandard->status==0)? "selected='selected'":"" ?>>Inactive</option>
										<option value="1" <?= ($editstandard->status==1)? "selected='selected'":"" ?> >Active</option>
									</select>
								</div>
						</div>
					<input type="hidden" name="string_id" value="{{$editstandard->string_id}}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<center><button type="submit" value="Publish" class="btn btn-info">Save</button></center>
					
			
			</form>
		
		           </div>
           </div>
    </div>
</div>
		
		
		
		<div class="spacer"></div>
		
<script type="text/javascript">
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