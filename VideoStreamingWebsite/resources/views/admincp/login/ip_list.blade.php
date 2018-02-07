@extends('admincp.master')
@section('title',"Banned IP Address Management")
@section ('subtitle',"User Management")
@section('content')
@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4> @endif
@if(session('msgerror'))<h4 class="alert alert-success">{{ session('msgerror') }}</h4> @endif
		<article class="module width_full">
			
			<div class="add"><a href="{{URL('admincp/add-banip')}}"><i class="fa fa-plus-circle"></i> Ban an IP Address</a></div>
		<header><h3 class="tabs_involved">Current list of Banned IP Addresses</h3></header>
			<table class="tablesorter" cellspacing="0" cellpadding="0"> 
			<thead> 
				<tr> 
    				<th class="line_table">ID</th> 
    				<th class="line_table">IP Address </th> 
    				<!-- <th class="line_table">Status</th> -->
    				<th >Action</th>
				</tr> 
			</thead> 
			<tbody> 
                @foreach($ipban as $result)
				<tr> 
    				<td class="line_table">{{$result->id}}</td> 
    				<td class="line_table">{{$result->ip_ban}}</td> 
    				<!-- <td class="line_table">
    					@if($result->status==1)
						<label class="label label-danger">Block</label>
    					@else
						<label class="label label-info">Open</label>
    					@endif	
    				</td>  -->
    				<td>
    					<a href="{{URL('admincp/edit-banip/')}}/{{$result->id}}"><i style="font-size: 20px ; margin: 5px" class="fa fa-pencil-square-o"></i></a>
    					<a href="{{URL('admincp/delete-banip/')}}/{{$result->id}}"  onclick="return confirm('Are you sure remove ipban : {{$result->ip_ban}} ?')"><i style="font-size: 20px ; margin: 5px" class="fa fa-trash-o"></i></a>
    				</td>
    				
				</tr> 
				 
				@Endforeach
			</tbody> 
			</table>
			<div id="pager" class="pager">
				<form>
					<img src="{{URL('public/assets/css/table/first.png')}}" class="first"/>
					<img src="{{URL('public/assets/css/table/prev.png')}}" class="prev"/>
					<input type="text" readonly="readonly" class="pagedisplay"/>
					<img src="{{URL('public/assets/css/table/next.png')}}" class="next"/>
					<img src="{{URL('public/assets/css/table/last.png')}}" class="last"/>
					<select class="pagesize">
						<option selected="selected"  value="5">5</option>
						<option value="10">10</option>
						<option value="20">20</option>
						<option  value="30">30</option>
					</select>
				</form>
			</div>
		</article><!-- end of content manager article -->
		
		
		
		<div class="spacer"></div>


@endsection