@extends('admincp.master')
@section('title',"Member")
@section('content')
<h4 class="alert_info">Welcome to Member administrator </h4>
		<article class="module width_full">
		<!-- 	<div class="add"><a href="{{URL('admincp/add-categories')}}"><i class="fa fa-plus-circle"></i> Add Categories</a></div> -->
		<header><h3 class="tabs_involved">All member</h3></header>
			<table class="tablesorter" cellspacing="0" cellpadding="0"> 
			<thead> 
				<tr> 
    				<th class="line_table">#</th>  
    				<th class="line_table">Member Name</th>
    				<th class="line_table">Email</th>
    				<th class="line_table">created_at</th>
    				<th >Action</th>
				</tr> 
			</thead> 
			<tbody> 
				<?php $i=1; ?>
                @foreach($member as $result)
				<tr> 
    				<td class="line_table">{{$i++}}</td> 
    				<td class="line_table">{{$result->firstname}} {{$result->lastname}}</td> 
    				<td class="line_table">{{$result->email}}</td>
    				<td class="line_table">{{$result->created_at}}</td> 
    				<td>
    					<a href="{{URL('admincp/edit-member&id=')}}{{$result->id}}"><img src="{{URL('public/assets/images/icn_edit.png')}}" title="Edit" /></a>
    					<a href="{{URL('admincp/delete-user/')}}/{{$result->id}}"><img type="image" src="{{URL('public/assets/images/icn_trash.png')}}" title="Trash"></a>
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