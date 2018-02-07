@extends('admincp.master')
@section('title',"Email Templates ")
@section ('subtitle',"Settings")
@section('content')
		<article class="module width_full">
			@if(session('msg-success'))<div class="alert alert-success"><span class="fa fa-check"></span><strong> {{session('msg-success')}}</strong></div>@endif
			@if(session('msg-error'))<div class="alert alert-danger"><span class="fa fa-times"></span><strong> {{session('msg-error')}}</strong></div>@endif
			<div class="add"><a href="{{URL('admincp/add-email-templete')}}"><i class="fa fa-plus-circle"></i> Add template</a></div>
		<header><h3 class="tabs_involved">Email Template</h3></header>
			<table class="tablesorter" cellspacing="0" cellpadding="0"> 
			<thead> 
				<tr> 
    				<th class="line_table">#</th> 
    				<th class="line_table">Name</th>
    				<th class="line_table">Description</th>
    				<th class="line_table">Status</th>
    				<th >Action</th>
				</tr> 
			</thead> 
			<tbody> 
				<?php $i=1 ?>
                @foreach($email_temp as $result)

				<tr> 
    				<td class="line_table">{{$i++}}</td> 
    				<td class="line_table">{{$result->name}}</td>
    				<td class="line_table">{{$result->description}}</td>
    				<td class="line_table">
    					<?= ($result->status)? '<span class="label label-success">Active</span>':'<span class="label label-danger">Block</span>'?>
    				</td>
    				<td>
    					<a href="{{URL('admincp/edit-email-templete&id=')}}{{$result->id}}"><i style="font-size: 20px ; margin: 5px" class="fa fa-pencil-square-o"></i></a>
    					<a href="{{URL('admincp/del-email-templete&id=')}}{{$result->id}}"  onclick="return confirm('Are you sure remove Ads : {{$result->name}} ?')"><i style="font-size: 20px ; margin: 5px" class="fa fa-trash-o"></i></a>
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