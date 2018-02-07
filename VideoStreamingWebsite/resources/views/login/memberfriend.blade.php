@if(isset($allfriend))
<div id="friend-result">
	<table class="table table-striped">
		<thead> 
			<tr> 
				<th >#</th> 
				<th >{{trans('home.FRIENDS')}}</th> 
				<th >{{trans('home.STATUS')}}</th>
				<th >{{trans('home.ACTION')}}</th>
			</tr> 
		</thead>
		<tbody>
			
			@if($allfriend->count() >0)
			<?php $i=1; ?>
			@foreach($allfriend as $result)
			<?php 
			$member_name=GetMemberName($result->member_friend);
			?>
			<tr>
				<td >{{$i++}}</td>
				<td >{{$member_name->firstname." ".$member_name->lastname}}</td>
				<td  >
					<?php
					if($result->status==1){
						echo"<span class='label label-success'>".trans('home.APPROVED')."</span>";
					}else if($result->status==0){
						echo"<span  class='label label-info'>".trans('home.PENDING')."</span>";
					}else if($result->status==2){
						echo"<span  class='label label-danger'>".trans('home.BLOCK')."</span>";
					}
					?>
				</td>
				<td >
					<?php
					if ($result->status == 1) {

						echo "<i class='fa fa-lock cl_red pointer' id='change-block' friend='$result->member_friend' ></i>";
					} else if ($result->status == 0) {
						echo"<i class='fa fa-check cl_green pointer' id='change-block'  friend='$result->member_friend'></i>";
					} else if ($result->status == 2) {
						echo"<i class='fa fa-unlock cl_green pointer' id='change-block' friend='$result->member_friend'></i>";
					}
					?>
				</td>
				
			</tr>
			@endforeach
			@else
			<tr>
				<td style="color:#ec567b; text-align: center;" colspan="4" rowspan="" headers="">{{trans('home.YOU_DO_NOT_HAVE_FRIEND')}}</td>
			</tr>	
			@endif
			
		</tbody> 

	</table>
	<div class="page_navigation">
		{!!$allfriend->render()!!}
	</div>

</div>				    
@endif