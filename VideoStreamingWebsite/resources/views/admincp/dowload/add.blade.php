@extends('admincp.master')
@section('title',"Download Video Content")
@section ('subtitle',"Video Management")
@section('content')
<div class="row">
	<div class="col-md-12">
		<ul class="nav nav-tabs">
			<li class="{{(session('addvideotype') == 'grab' || session('addvideotype') == '') ? 'active' : ''}}">
				<a data-toggle="tab" href="#home"><i class="fa fa-download"></i> Grab-Data Link</a>
			</li>
			<li class="{{session('addvideotype') == 'import' ? 'active' : ''}}">
				<a data-toggle="tab" href="#menu1"><i class="fa fa-upload"></i> Import CSV</a>
			</li>
		</ul>

		<div class="tab-content">
			<div id="home" class="tab-pane fade {{(session('addvideotype') == 'grab' || session('addvideotype') == '') ? 'in active' : ''}} ">
				<div class="modal-dialog col-md-12" style="width:100% !important">
					@if(session('success'))
					<div class="alert alert-success">{{session('success')}}</div>
					@endif

					@if ($errors->has() && session('addvideotype') == 'grab')
					<div class="alert alert-danger">
						@foreach ($errors->all() as $error)
						{{ $error }}<br>
						@endforeach
					</div>
					@endif
					<div class="panel panel-primary">
						<div class="panel-heading">Download Video Content</div>
						<div class="panel-body">
							<form action="{{URL('admincp/dowload-video-add')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<div class="form-group">
									<div class="col-md-2"><label class="label-control">Website</label></div>
									<div class="col-md-10">
										<select name="website" class="form-control">
											<option value="www.xvideos.com">xvideos.com</option>
											{{-- <option value="upornia.com">upornia.com</option> --}}
											{{-- <option value="www.maxjizztube.com">www.maxjizztube.com</option> --}}
											<option value="fapbox.com">fapbox.com</option>
											{{-- <option value="h2porn.com">h2porn.com</option> --}}
											<option value="www.txxx.com">www.txxx.com</option>
											<option value="www.pornhub.com">www.pornhub.com</option>
											{{-- <option value="www.pornhd.com">www.pornhd.com</option> --}}
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-2"><label class="label-control required-field">Link Video URL</label></div>
									<div class="col-md-10">
										<input type="text" name="link" class="form-control" value="" placeholder="Link url video ">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-2"><label class="label-control">Uploaded By</label></div>
									<div class="col-md-10">
										<?php if (\Session::has('logined')): ?>
											<?php
											$user = \Session::get('logined');
											?>
											<input type="text" name="username" class="form-control" value="<?php echo $user->username; ?>" placeholder="Username">
										<?php else: ?>
											<input type="text" name="username" class="form-control" value="" placeholder="Username">
										<?php endif ?>
									</div>
								</div>

								<div class="form-group">
									<div class="col-md-2"><label class="control-label">Categories</label></div>
									<div class="col-md-10">
										<select id="categories_Id" multiple="multiple" class="form-control" name="post_result_cat[]">
											@foreach ($categories as $cate_result)
											<option  data-name="{{$cate_result->title_name}}" value="{{$cate_result->id}}_{{$cate_result->title_name}}">{{$cate_result->title_name}}</option>
											@endforeach
										</select>
										<script type="text/javascript">
											$(document).ready(function(){$('#categories_Id').select2(); })
										</script>
									</div>
								</div>
							<div class="form-group">
								<div class="col-md-2"><label class="label-control">Channel</label></div>
								<div class="col-md-10">
									<select name="channel" class="form-control">
										<option></option>
										<?php if (!empty($channel)): ?>
											<?php foreach ($channel as $key => $result): ?>
												<option value="<?php echo $result->id; ?>"><?php echo $result->title_name; ?></option>
											<?php endforeach ?>
										<?php endif ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-2"><label class="label-control">Pornstar</label></div>
								<div class="col-md-10">
									<select name="pornstar" class="form-control">
										<option></option>
										<?php if (!empty($pornstar)): ?>
											<?php foreach ($pornstar as $key => $result): ?>
												<option value="<?php echo $result->id; ?>"><?php echo $result->title_name; ?></option>
											<?php endforeach ?>
										<?php endif ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-2"><label class="label-control">Status</label></div>
								<div class="col-md-10">
									<select name="status" class="form-control">
										<option selected value="1">Active</option>
										<option value="0">InActive</option>
									</select>
								</div>
							</div>

							<center><button type="submit" name="save" value="Save" class="btn btn-info">Save</button></center>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div id="menu1" class="tab-pane fade {{session('addvideotype') == 'import' ? 'in active' : ''}}">
			<div class="modal-dialog col-md-12" style="width:100% !important">
				@if(session('success'))
				<div class="alert alert-success">{{session('success')}}</div>
				@endif

				@if ($errors->has() && session('addvideotype') == 'import')
				<div class="alert alert-danger">
					@foreach ($errors->all() as $error)
					{{ $error }}<br>
					@endforeach
				</div>
				@endif
				<div class="panel panel-primary">
					<div class="panel-heading">Import CSV File</div>
					<div class="panel-body">

						<form action="{{URL('admincp/import-video')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							<div class="form-group">
								<div class="col-md-2"><label class="label-control">Website</label></div>
								<div class="col-md-10">
									<select id="selectWebsite" name="website" class="form-control">
										{{-- <option value="www.pornhub.com">www.pornhub.com</option>
										<option value="www.redtube.com">www.redtube.com</option>
										<option value="www.tube8.com">www.tube8.com</option>
										<option value="www.youporn.com">www.youporn.com</option> --}}

										<option value="www.pornhub.com">Pornhub (hubtraffic)</option>
										<option value="www.redtube.com">Redtube (hubtraffic)</option>
										<option value="www.tube8.com">Tube8 (hubtraffic)</option>
										<option value="www.youporn.com">YourPorn (hubtraffic)</option>

										<option value="hclips.com">HClips.com (tubecorporate.com)</option>
										<option value="hdzog.com">HdZog.com (tubecorporate.com)</option>
										<option value="hotmovs.com">HotMovs.com (tubecorporate.com)</option>
										<option value="thegay.com">TheGay.com (tubecorporate.com)</option>
										<option value="tubepornclassic.com">TubePornClassic.com (tubecorporate.com)</option>
										<option value="txxx.com">Txxx.com (tubecorporate.com)</option>
										<option value="upornia.com">Upornia.com (tubecorporate.com)</option>
										<option value="vjav.com">Vjav.com (tubecorporate.com)</option>
										<option value="voyeurhit.com">VoyeurHit.com (tubecorporate.com)</option>

									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-2"><label class="label-control required-field">Select File</label></div>
								<div class="col-md-10 ">
									<span class="btn btn-info btn-file"> Select CSV File
										<input type="file" name="csv_file" id="fileSelectCSV" class="form-control" value="" placeholder="Link url video" accept=".csv">
									</span>
									<span id="fileSelectCSVInfo"></span>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-2"><label class="label-control">Uploaded By</label></div>
								<div class="col-md-10">
									<?php if (\Session::has('logined')): ?>
										<?php
										$user = \Session::get('logined');
										?>
										<input type="text" name="username" class="form-control" value="<?php echo $user->username; ?>" placeholder="Username">
									<?php else: ?>
										<input type="text" name="username" class="form-control" value="" placeholder="Username">
									<?php endif ?>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-2"><label class="control-label">Categories</label></div>
								<div class="col-md-10">
									<select id="categories_Id_import" style="width:100%;" multiple="multiple" class="form-control" name="post_result_cat_import[]">
										@foreach ($categories as $cate_result)
										<option  data-name="{{$cate_result->title_name}}" value="{{$cate_result->id}}_{{$cate_result->title_name}}">{{$cate_result->title_name}}</option>
										@endforeach
									</select>
									<script type="text/javascript">
										$(document).ready(function(){$('#categories_Id_import').select2(); })
									</script>

								</div>
							</div>

							<div class="form-group">
								<div class="col-md-2"><label class="label-control">Channel</label></div>
								<div class="col-md-10">
									<select name="channel_import" class="form-control">
										<option></option>
										<?php if (!empty($channel)): ?>
											<?php foreach ($channel as $key => $result): ?>
												<option value="<?php echo $result->id; ?>"><?php echo $result->title_name; ?></option>
											<?php endforeach ?>
										<?php endif ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-2"><label class="label-control">Pornstar</label></div>
								<div class="col-md-10">
									<select name="pornstar_import" class="form-control">
										<option></option>
										<?php if (!empty($pornstar)): ?>
											<?php foreach ($pornstar as $key => $result): ?>
												<option value="<?php echo $result->id; ?>"><?php echo $result->title_name; ?></option>
											<?php endforeach ?>
										<?php endif ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-2"><label class="label-control">Status</label></div>
								<div class="col-md-10">
									<select name="status" class="form-control">
										<option  selected value="1">Active</option>
										<option value="0">InActive</option>
									</select>
								</div>
							</div>

							<center><button type="submit" name="save" id="import_video" value="Save" class="btn btn-info">Import Video</button></center>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="jax-loading" style="" class="modal fade" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<center><img width="32" height="32" src="{{URL('public/assets/images/loading_apple.gif')}}"/></center>
	</div>
</div>
{{session()->forget('addvideotype')}}
<script type="text/javascript">
$('#import_video').click(function(){
	$('#jax-loading').modal('show');
})
// $('#selectWebsite').on('change',function(){

// 	if($(this).val()=='www.spankwire.com'){
// 		$('#fileSelectCSV').attr('accept','.json');
// 		// $('#fileSelectCSV').parent().html('Select JSON File <input type="file" name="csv_file" id="fileSelectCSV" class="form-control" value="" placeholder="Link url video" accept=".json">');
// 	}else{
// 		$('#fileSelectCSV').attr('accept','.csv');
// 		// $('#fileSelectCSV').parent().html('Select CSV File <input type="file" name="csv_file" id="fileSelectCSV" class="form-control" value="" placeholder="Link url video" accept=".csv">');
// 	}

// });
</script>

@endsection
