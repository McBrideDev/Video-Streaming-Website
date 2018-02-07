@extends('admincp.master')
@section('title',"Translator Language")
@section ('subtitle',"Language Management")
@section('content')
<div class="row ">
	<div class="modal-dialog col-md-12" style="width:100% !important">
		@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
		@if(session('msgerror'))<div class="alert alert-danger">{{session('msgerror')}}</div>@endif
		<div class="panel panel-primary">
			<div class="panel-heading">Add Translator {{$language->languageName}} </div>
			<div class="panel-body">
				<form method="POST" action="/admincp/language/stranlate/{{$language->id}}/add">
					<div class="form-group">
						<div class="row">
							<div class="col-sm-2"><input class="form-control" type="text" placeholder="Key" name="dataKey"></div>
							<div class="col-sm-10"><input type="text" name="dataValue" class="form-control" placeholder="Value"></div>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
						</div>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-danger">Add</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="spacer"></div>

<style type="text/css" media="screen">

	.update-value, .cancle-value{
		font-size: 20px;
		position: relative;
		top: -5px;
		padding: 2px 10px;
		color: #fff !important;
		margin: 5px
	}
	.cancle-value{
		left:-5px;
	}


</style>
@endsection