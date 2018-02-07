@extends('admincp.master')
@section('title',"Edit Language")
@section ('subtitle',"Language Management")
@section('content')
<div class="row ">
	<div class="modal-dialog col-md-12" style="width:100% !important">
		@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
		@if(session('msgerror'))<div class="alert alert-danger">{{session('msgerror')}}</div>@endif
		<div class="panel panel-primary">
			<div class="panel-heading">Edit Language </div>
			<div class="panel-body">
				<?= Form::open(['url' => url('admincp/language/update/'.$language->id), 'method'=> 'POST', 'files' => true, 'class'=>'form-horizontal']) ?>
    			<div class="form-group {{$errors->has('languageName')? 'has-error':''}}">
    				<div class="col-md-2"><?=Form::label('Language Name')?></div>
    				<div class="col-md-10">
    					<?= Form::text('languageName', $language->languageName, ['required', 'class'=>'form-control', 'placeholder'=>'Language name']) ?>
    					<span class="required help-block">{{$errors->first('languageName')}}</span>
    				</div>
    			</div>
    			<div class="form-group {{$errors->has('languageCode')? 'has-error':''}}">
    				<div class="col-md-2"><?=Form::label('Language Code')?></div>
    				<div class="col-md-10">
    					<?= Form::text('languageCode', $language->languageCode, ['required', 'class'=>'form-control', 'placeholder'=>'Language code']) ?>
    					<span class="required help-block">{{$errors->first('languageCode')}}</span>
    				</div>
    			</div>
    			<div class="form-group">
    				<div class="col-md-2"><?=Form::label('Status')?></div>
    				<div class="col-md-10"><?= Form::select('status',['active' => 'Active', 'inactive' => 'Inactive'],$language->status ,array('class' => 'form-control')) ?></div>
    			</div>
    			<center><?= Form::submit('Save', array('class'=>'btn btn-info'))?></center>
				<?= Form::close() ?>
			</div>
		</div>
	</div>
</div>
<div class="spacer"></div>
<script type="text/javascript">
	$(document).ready(function(){
		$('.tablesorter1').DataTable();
	})
</script>

@endsection