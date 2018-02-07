@extends('admincp.master')
@section('title',"PornStar")
@section('content')
<h4 class="alert_info">

    Welcome to pornstar administrator 
</h4>
		<article class="module width_full">
			<div class="add"><a href="{{URL('admincp/add-pornstar')}}"><i class="fa fa-plus-circle"></i> Add PornStar</a></div>
		<header><h3 class="tabs_involved">PornStar Subscriber</h3></header>
			<table class="tablesorter" cellspacing="0" cellpadding="0"> 
			<thead> 	
				<tr> 
    				<th class="line_table">ID</th> 
    				<th class="line_table">Porm Name</th> 
    				<th class="line_table">Total subscriber</th>
    				<!-- <th >Action</th> -->
				</tr> 
			</thead> 
			<tbody> 
                @foreach ($pornstarsubscribe as $result)
                <?php $total= explode(',', $result->member_Id); ?>
				<tr> 
    				<td class="line_table">{{$result->id}}</td> 
    				<td class="line_table">{{$result->title_name}}</td> 
    				<td class="line_table">{{sizeof($total)}} Member</td> 
    				<!-- <td>
    					<a href="/admincp/edit-channel/{{$result->ID}}"><img src="/public/assets/images/icn_edit.png" title="Edit" /></a>
    					<a href="/admincp/delete-channel/{{$result->ID}}"><img type="image" src="/public/assets/images/icn_trash.png" title="Trash"></a>
    				</td> -->
    				
				</tr> 
                @endforeach
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