@extends('admincp.master')
@section('title',"Manage Existing Users")
@section ('subtitle',"User Management")
@section('content')
<div class="row ">
	<div class="modal-dialog col-md-12" style="width:100% !important">
		@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
		@if(session('msgerror'))<div class="alert alert-danger">{{session('msgerror')}}</div>@endif
		<div class="panel panel-primary">
		<div class="panel-heading">Registered Users  <span class="pull-right"> <a class="btn-organ" href="{{URL('admincp/add-member')}}"><i class="fa fa-plus-circle"></i> Add A New User </a></span> <span class="pull-right"> <a class="btn-organ" id="deleteall" href="javascript:void(0)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete Users </a></span> </div>
			<div class="panel-body">
				<table class="tablesorter1 table table-hover table-striped" cellspacing="0" cellpadding="0"> 
					<thead> 
						<tr> 
							<th class="line_table"><input type="checkbox" id="checkall" name="checkall" value=""></th>
							<!-- <th style="min-width: 50px;" class="line_table">ID</th> --> 
							<th class="line_table">Username</th> 
							<th class="line_table">Name</th>
							<th class="line_table">Email</th>
							<th class="line_table">Created On</th>
							<!-- <th class="line_table">Status</th> -->
							<th >Action</th>
						</tr> 
					</thead> 
					<tbody> 
						<?php $i=1; ?>
						@foreach($member as $result)
						<tr>
							<td class="line_table"><input name="check" value="{{$result->id}}" type="checkbox" ></td> 
							<!-- <td class="line_table">{{$i++}}</td>  -->
							<td class="line_table">{{$result->username}}</td> 
							<td class="line_table">{{$result->firstname}} {{$result->lastname}}</td> 
							<td class="line_table">{{$result->email}}</td>
							<td class="line_table">
								<?php
								$date= new datetime($result->created_at);
								$format= $date->format('M d, H:i');
								echo $format;
								?>
							</td>

							<td class="align-center">
								<span class="btn-group">
									<a href="{{URL('admincp/edit-user/')}}/{{$result->id}}" data-toggle="tooltip" data-placement="top" title="Edit" ><i style="font-size: 20px ; margin: 5px" class="fa fa-pencil-square-o"></i></a>
									<a href="{{URL('admincp/delete-user/')}}/{{$result->id}}" data-toggle="tooltip" data-placement="top" title="Delete" ><i style="font-size: 20px ; margin: 5px" class="fa fa-trash-o"></i></a>
									<!-- <a href="javascript:void(0);" id="approve" data-id="{{$result->id}}" data-toggle="tooltip" data-placement="top" title="Approve" ><i style="font-size: 20px ; margin: 5px" class="fa fa-check"></i></a> -->
									<a href="javascript:void(0);" id="block" data-id="{{$result->id}}" data-toggle="tooltip" data-placement="top" title="Block" ><i style="font-size: 20px ; margin: 5px" class="fa fa-lock"></i></a>
								</span>
							</td>

						</tr> 

						@Endforeach
					</tbody> 
				</table>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('.tablesorter1').DataTable({
		"pageLength": 20,
		'columnDefs': [{
			'targets': 0,
			'bSortable': false,
			'searchable':false,
			'orderable':false,
		}]
	});
	$('#example-select-all').on('checkall', function(){
		      // Check/uncheck all checkboxes in the table
		      var rows = table.rows({ 'search': 'applied' }).nodes();
		      $('input[type="checkbox"]', rows).prop('checked', this.checked);
		  });
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})

	$(document).on('click','#block',function (){
		var data_id= $(this).attr('data-id');
		$.ajax({
			url:"{{URL('admincp/block/id=')}}"+data_id,
			success:function(data){
				$('#change-status_'+data_id+'').empty().append(data);		
			}
		})
	})

	$(document).on('click','#approve',function (){
		var data_id= $(this).attr('data-id');
		$.ajax({
			url:"{{URL('admincp/approve/id=')}}"+data_id,
			success:function(data){
				$('#change-status_'+data_id+'').empty().append(data);		
			}
		})
	})


	$("#checkall").click(function () {
		var status = this.checked;
		$("input[name='check']").each(function () {
			this.checked = status;
		})
	});

	$("#deleteall").click(function () {
		var listid = "";
		$("input[name='check']").each(function () {
			if (this.checked)
				listid = listid + "," + this.value;
		})
				listid = listid.substr(1);	 //alert(listid);
				if (listid == "") {
					alert("Please check item on list !");
					return false;
				}
				getaction = confirm("Are you sure delete all check?");
				if (getaction == true)
					document.location = "{{URL('admincp/user/delete/')}}/" + listid;
			});

		</script>

		@endsection
