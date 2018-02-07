@extends('admincp.master')
@section('title',"Conversation Settings")
@section ('subtitle',"Settings")
@section('content')
<div class="row ">
		<div class="modal-dialog col-md-12" style="width:100% !important">
		    @if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
			@if(session('msgerro'))<div class="alert alert-danger">{{session('msgerro')}}</div>@endif
       		<div class="panel panel-primary">
               	<div class="panel-heading">Video Conversion</div>
	                <div class="panel-body">
				<form action="{{URL('admincp/conversion-config')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				
			
						<div class="form-group">
							<div class="col-md-3"><label class="control-label">PHP CLI path</label></div>
							<div class="col-md-9"><input class="form-control"  type="text" name="php_cli_path" value="{{$config->php_cli_path}}"></div>
						</div>

						<div class="form-group">
							<div class="col-md-3"><label class="control-label">Mplayer Path</label></div>
							<div class="col-md-9"><input class="form-control"  type="text" name="mplayer_path" value="{{$config->mplayer_path}}"></div>
						</div>
						<div class="form-group">
							<div class="col-md-3"><label class="control-label">Mencoder path</label></div>
							<div class="col-md-9"><input class="form-control"  type="text" name="mencoder_path" value="{{$config->mencoder_path}}"></div>
						</div>
						<div class="form-group">
							<div class="col-md-3"><label class="control-label">FFmpeg path</label></div>
							<div class="col-md-9"><input class="form-control"  type="text" name="ffmpeg_path" value="{{$config->ffmpeg_path}}"></div>
						</div>
						<div class="form-group">
							<div class="col-md-3"><label class="control-label">FLV tool2 path</label></div>
							<div class="col-md-9"><input class="form-control"  type="text" name="flvtool2_path" value="{{$config->flvtool2_path}}"></div>
						</div>
						<div class="form-group">
							<div class="col-md-3"><label class="control-label">MP4Box path</label></div>
							<div class="col-md-9"><input class="form-control"  type="text" name="mp4box_path" value="{{$config->mp4box_path}}"></div>
						</div>
						<div class="form-group">
							<div class="col-md-3"><label class="control-label">Mediainfo path</label></div>
							<div class="col-md-9"><input class="form-control"  type="text" name="mediainfo_path" value="{{$config->mediainfo_path}}"></div>
						</div>
						<div class="form-group">
							<div class="col-md-3"><label class="control-label">Yamdi path</label></div>
							<div class="col-md-9"><input class="form-control"  type="text" name="yamdi_path" value="{{$config->yamdi_path}}"></div>
						</div>
						<div class="form-group">
							<div class="col-md-3"><label class="control-label">Thumnail generation tool</label></div>
							<div class="col-md-9">
								<select name="thumbnail_tool">
									<option value="ffmpeg" <?=($config->thumbnail_tool=="ffmpeg" )? 'selected="selected"' : '' ?>   >FFmpeg</option>
									<option value="yamdi" <?=($config->thumbnail_tool=="yamdi" )? 'selected="selected"' : '' ?>   >Yamdi</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-3"><label class="control-label">META Injection Tool</label></div>
							<div class="col-md-9">
								
								<select name="meta_injection_tool">
									<option value="flvtool2" <?=($config->meta_injection_tool=="flvtool2" )? 'selected="selected"' : '' ?>   >FLV Tool2</option>
									<option value="yamdi" <?=($config->meta_injection_tool=="yamdi" )? 'selected="selected"' : '' ?>   >Yamdi</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-3"><label class="control-label">Max thumbnail width</label></div>
							<div class="col-md-9"><input class="form-control"  type="text" name="max_thumbnail_w" value="{{$config->max_thumbnail_w}}"></div>
						</div>
						<div class="form-group">
							<div class="col-md-3"><label class="control-label">Max thumbnail height</label></div>
							<div class="col-md-9"><input class="form-control"  type="text" name="max_thumbnail_h" value="{{$config->max_thumbnail_h}}"></div>
						</div>
						<div class="form-group">
							<div class="col-md-3"><label class="control-label">Allowed Video Extensions</label></div>
							<div class="col-md-9"><input class="form-control"  type="text" name="allowed_extension" value="{{$config->allowed_extension}}"></div>
						</div>

						<div class="form-group">
							
								<input type="hidden" name="id" value="{{$config->id}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
						
							<center><button type="submit" value="Publish" class="btn btn-info">Save</button></center>
						</div>

			</form>
		</article><!-- end of post new article -->
		
		
		
		<div class="spacer"></div>
		

@endsection