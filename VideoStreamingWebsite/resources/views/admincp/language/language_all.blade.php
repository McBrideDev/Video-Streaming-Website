@extends('admincp.master')
@section('title',"All language")
@section ('subtitle',"Language Managerment")
@section('content')
<div class="row ">
	<div class="modal-dialog col-md-12" style="width:100% !important">
		@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
		@if(session('msgerror'))<div class="alert alert-danger">{{session('msgerror')}}</div>@endif
		<div class="panel panel-primary">
			<div class="panel-heading">All Language <span class="pull-right"> <a class="btn-organ" href="{{URL('admincp/language/add')}}"><i class="fa fa-plus-circle"></i> Add A New language </a></span></div>
			<div class="panel-body">
				<table class="tablesorter1" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<th  class="line_table text-center">Language Name</th>
							<th class="line_table text-center">Language Code</th>
							<th class="line_table text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
					@foreach($languageList as $result )
						<tr>
	    					<td class="line_table text-center">{{$result->languageName}}</td>
	    					<td class="line_table text-center">{{$result->languageCode}}</td>
	    					<td class="line_table text-center">
	    						@if($result->status === 'active')
	    						<span class="label label-success"> Active</span>
	    						@endif
	    						@if($result->status === 'inactive')
	    						<span class="label label-danger"> Inactive</span>
	    						@endif
	    					</td>
	    					<td class="text-center">
	    						<span class="btn-group">
		    						<a href="{{URL('admincp/language/edit/'.$result->id)}}""><i style="font-size: 18px ; margin: 5px" class="fa fa-pencil-square-o"></i></a>
	                   				<a href="{{URL('admincp/language/delete/'.$result->id)}}" onclick="return confirm('Are you sure remove this : {{$result->languageName}} ?')"><i style="font-size: 20px ; margin: 5px" class="fa fa-trash-o"></i></a>
	                   				<a href="{{URL('admincp/language/stranlate/'.$result->id)}}" ><i style="font-size: 20px ; margin: 5px" class="fa fa-language"></i></a>
	    						</span>
	    					</td>
						</tr>
					@endforeach
					</tbody>
				</table>
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