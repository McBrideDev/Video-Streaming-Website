@extends('admincp.master')
@section('title',"Video Settings")
@section ('subtitle',"Settings")
@section('content')
<div class="row ">
		<div class="modal-dialog col-md-12" style="width:100% !important">
		    @if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
			@if(session('msgerror'))<div class="alert alert-danger">{{session('msgerror')}}</div>@endif
       		<div class="panel panel-primary">
               	<div class="panel-heading">Video Settings Configuration</div>
	                <div class="panel-body">
				<form action="{{URL('admincp/video-setting')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">


						<div class="form-group">
							<div class="col-md-2"><label class="control-label" style="text-align: left;">Enable Subscriptions</label></div>
							<div class="col-md-10">
								<select name="is_subscribe" class="form-control">
										<option value="1" <?=($v_setting->is_subscribe==1)? 'selected="selected"':''  ?> >Enable</option>
										<option value="0" <?=($v_setting->is_subscribe==0)? 'selected="selected"':''  ?> >Disable</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Enable Favorites</label></div>
							<div class="col-md-10">
								<select name="is_favorite" class="form-control">
										<option value="1" <?=($v_setting->is_favorite==1)? 'selected="selected"':''  ?> >Enable</option>
										<option value="0" <?=($v_setting->is_favorite==0)? 'selected="selected"':''  ?> >Disable</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Enable Downloads</label></div>
							<div class="col-md-10">
								<select name="is_download" class="form-control">
										<option value="1" <?=($v_setting->is_download==1)? 'selected="selected"':''  ?> >Enable</option>
										<option value="0" <?=($v_setting->is_download==0)? 'selected="selected"':''  ?> >Disable</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Enable Embedding</label></div>
							<div class="col-md-10">

								<select name="is_embed" class="form-control">
										<option value="1" <?=($v_setting->is_embed==1)? 'selected="selected"':''  ?> >Enable</option>
										<option value="0" <?=($v_setting->is_embed==0)? 'selected="selected"':''  ?> >Disable</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Enable Ads</label></div>
							<div class="col-md-10">

								<select name="is_ads" class="form-control">
										<option value="1" <?=($v_setting->is_ads==1)? 'selected="selected"':''  ?> >Enable</option>
										<option value="0" <?=($v_setting->is_ads==0)? 'selected="selected"':''  ?> >Disable</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label" style="text-align: left;">Amount of time user has to wait to Skip an Ad</label></div>
							<div class="col-md-10"><input class="form-control"  type="number" min="3" name="time_skip_ads" value="{{$v_setting->time_skip_ads}}"></div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label"> Reload Video</label></div>
							<div class="col-md-10">

								<select name="video_reload" class="form-control">
										<option value="1" <?=($v_setting->video_reload==1)? 'selected="selected"':''  ?> >Enable</option>
										<option value="0" <?=($v_setting->video_reload==0)? 'selected="selected"':''  ?> >Disable</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Logo in-player</label></div>
							<div class="col-md-10">
								<span class="btn btn-info btn-file"> Select File
									<input class="form-control"  type="file" id="fileSelect" name="player_logo" >
								</span>
								 <span id="fileSelectInfo"></span>
								<?php
								if($v_setting->player_logo!=NULL){?>
									<div style="background: #ddd; padding: 5px;">
										<img src="{{asset('public/upload/player/'.$v_setting->player_logo)}}" style="height: 50px">
									</div>
								<?php }?>
							</div>
						</div>
						<!-- <div class="form-group" >
							<div class="col-md-2"><label class="control-label">Player Loading</label></div>
							<div class="col-md-10">
							<span class="btn btn-info btn-file"> Select File
								<input  class="form-control" type="file" name="player_loading" >
							</span>
							<?php
								if($v_setting->player_loading!=NULL){?>
								<div style="background: #ddd; padding: 5px;">
									<img src="{{asset('public/upload/player/'.$v_setting->player_loading)}}" style="height: 50px">
								</div>
							<?php }?>
							</div>
						</div> -->
						<div class="form-group">

								<input type="hidden" name="id" value="{{$v_setting->id}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">

							<center><button type="submit" value="Publish" class="btn btn-info">Save</button></center>
						</div>

			</form>
		</article><!-- end of post new article -->



		<div class="spacer"></div>


@endsection