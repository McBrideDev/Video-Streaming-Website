@extends('admincp.master')
@section('title',"Edit An Existing Video Ad")
@section ('subtitle',"Advertisement Management")
@section('content')
<div class="row ">
	<div class="modal-dialog col-md-12" style="width:100% !important">
		@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
		@if(session('msgerro'))<div class="alert alert-danger">{{session('msgerro')}}</div>@endif
		<div class="panel panel-primary">
			<div class="panel-heading">Edit An Existing Video Ad</div>
				<div class="panel-body">
					<form action="{{URL('admincp/edit_in-player-media-ads')}}/{{$editvideoads->id}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Ad Name</label></div>
							<div class="col-md-10"><input class="form-control" type="text" name="title" value="{{$editvideoads->title}}" placeholder=""></div>
						</div>
						

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Description</label></div>
							<div class="col-md-10"><input class="form-control" type="text" name="descr" value="{{$editvideoads->descr}}" placeholder=""></div>
						</div>

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Ad URL</label></div>
							<div class="col-md-10"><input class="form-control" type="text" name="adv_url" value="{{$editvideoads->adv_url}}" placeholder=""></div>
						</div>

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Upload Media File</label></div>
							<div class="col-md-10"><input  class="form-control" type="file" name="media" ><small>(*.mp4)</small></div>
							<div class="col-md-2">&nbsp;</div>
							<div class="col-md-10">
								<video id="video" class="video-js vjs-default-skin"
								  controls preload="auto" width="640" height="264"
								  data-setup='{"example_option":true}'>
									<source src="{{$editvideoads->media}}" type="video/mp4" />
									<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
								</video>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Status</label></div>
							<div class="col-md-10">
								<select class="form-control" name="status">
									<option value="1" <?=($editvideoads->status==1)? 'selected="selected"' :''  ?> >Active</option>
									<option value="0" <?=($editvideoads->status==0)? 'selected="selected"': '' ?> >Inactive</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<input type="hidden" name="id" value="{{$editvideoads->id}}">
							<input type="hidden" name="string" value="{{$editvideoads->string_id}}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							
							<center><button type="submit" value="Publish" class="btn btn-info">Save</button></center>
						</div>

					</form>
		</article><!-- end of post new article -->
		
		<div class="spacer"></div>

@endsection

@section('script')
<link href="//vjs.zencdn.net/5.4.6/video-js.min.css" rel="stylesheet">
<script src="//vjs.zencdn.net/5.4.6/video.js"></script>
@endsection
