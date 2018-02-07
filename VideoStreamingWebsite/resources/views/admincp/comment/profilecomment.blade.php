@extends('admincp.master')
@section('title',"Comment")
@section('content')
<h4 class="alert_info">Welcome to profile comment administrator </h4>
		<article class="module width_full">
			<div class="add"><a href="/admincp/report-profile-comment"><i class="fa fa-bar-chart"></i> Report Profile Comment</a></div>
		<header><h3 class="tabs_involved">Profile Comment</h3></header>
			<table class="tablesorter" cellspacing="0" cellpadding="0"> 
			<thead> 
				<tr> 
    				<th class="line_table">Comment ID</th> 
    				<th class="line_table">Profile ID</th> 
                    <th class="line_table">Comment</th>
                    <th class="line_table">Date Post</th>
                    <th>Action</th>
				</tr> 
			</thead> 
			<tbody> 
				<tr> 
    				<td class="line_table">1</td> 
    				<td class="line_table">123</td> 
                    <td class="line_table">Lorem Ipsum Dolor Sit Amet</td> 
                    <td class="line_table">17/9/2015 4:56 PM</td> 
    				<td>
    					<a href="{{URL::to('/admincp/categories/edit')}}"><img src="/public/assets/images/icn_edit.png" title="Edit" /></a>
    					<a href="{{URL::to('/admincp/categories/delete')}}"><img type="image" src="/public/assets/images/icn_trash.png" title="Trash"></a>
    				</td>
    				
				</tr> 
				<tr> 
    				<td class="line_table">2</td> 
    				<td class="line_table">123</td> 
                    <td class="line_table">Lorem Ipsum Dolor Sit Amet</td> 
                    <td class="line_table">17/9/2015 4:56 PM</td> 
    				<td>
    					<a href="{{URL::to('/admincp/categories/edit')}}"><img src="/public/assets/images/icn_edit.png" title="Edit" /></a>
    					<a href="{{URL::to('/admincp/categories/delete')}}"><img type="image" src="/public/assets/images/icn_trash.png" title="Trash"></a>
    				</td>
    				
				</tr>
				<tr> 
    				<td class="line_table">3</td> 
    				<td class="line_table">123</td> 
                    <td class="line_table">Lorem Ipsum Dolor Sit Amet</td> 
                    <td class="line_table">17/9/2015 4:56 PM</td> 
    				<td>
    					<a href="{{URL::to('/admincp/categories/edit')}}"><img src="/public/assets/images/icn_edit.png" title="Edit" /></a>
    					<a href="{{URL::to('/admincp/categories/delete')}}"><img type="image" src="/public/assets/images/icn_trash.png" title="Trash"></a>
    				</td>
    				
				</tr> 
				
			</tbody> 
			</table>
		
		</article><!-- end of content manager article -->
		
		
		
		<div class="spacer"></div>


@endsection