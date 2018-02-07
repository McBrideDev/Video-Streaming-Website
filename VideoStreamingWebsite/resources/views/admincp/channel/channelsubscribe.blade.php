@extends('admincp.master')
@section('title',"Manage Channel Subscriptions")
@section ('subtitle',"Channel Management")
@section('content')
@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
@if(session('msgerro'))<div class="alert alert-error">{{session('msgerro')}}</div>@endif
		<article class="module width_full">
			
		<header><h3 class="tabs_involved">Current Channel Subscriptions </h3></header>
			<table class="tablesorter" cellspacing="0" cellpadding="0"> 
			<thead> 	
				<tr> 
    				<th class="line_table" style="text-align: center">ID</th> 
    				<th class="line_table" style="text-align: center">Channel Name</th> 
    				<th class="line_table" style="text-align: center">Total Number of Subscriptions</th>
    				<!-- <th >Action</th> -->
				</tr> 
			</thead> 
			<tbody> 
                @foreach ($channelsubscribe as $result)
                <?php $total= explode(',', $result->member_Id); 
                	if(count($total)==1){
                		$total_member=count($total)." Member";
                	}else{
                		$total_member=count($total)." Members";	
                	}
                ?>
				<tr> 
    				<td class="line_table" align="center">{{$result->id}}</td> 
    				<td class="line_table" align="center">{{$result->title_name}}</td> 
    				<td id="member-subscribe-{{$result->id}}" class="line_table" data-member="<?=implode(',', $total)?>" align="center"><a href="javascript:void(0);">{{$total_member}}</a></td> 
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
		
		<div id="member-list" style="position:absolute; bottom: 0; right:0" class="modal fade" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="panel panel-primary">
					<div class="panel-body">
							<div class="table-responsive">
								  <table class="table">
									  <thead>
									  		<th>Username</th>
									    	<th>Email</th>
									  </thead>
									   <tbody id="data-member-list">
									   		
									   </tbody>
								  </table>
							</div>
								
					</div>
					<div class="panel-footer">
						<center><input type="button" data-dismiss="modal" id="cancel" class="btn btn-info" style="margin-right: 5px; margin-top: 15px" value="Close"> </center>
					</div>
				</div>
			</div>
		</div>	
		
		<div class="spacer"></div>

	<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('click','[id*="member-subscribe"]',function() {

			var list_member=$(this).attr('data-member');
			$.ajax({
				url:"member-list-subscribe/"+list_member,
				success:function(data){
					if(data.length>10){
						$('#data-member-list').empty().append(data);
						$("#member-list").modal("show");
					}
					if(data==0){
						$('#data-member-list').empty().append("Member Not Found");
						$("#member-list").modal("show");
					}
				}
			})
		})
	})
	</script>
@endsection