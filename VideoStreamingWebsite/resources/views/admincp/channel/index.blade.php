@extends('admincp.master')
@section('title',"Manage an Existing Channel")
@section ('subtitle',"Channel Management")
@section('content')
@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
@if(session('msgerro'))<div class="alert alert-error">{{session('msgerro')}}</div>@endif
		<article class="module width_full">
            <div class="add">
                <a href="{{URL('admincp/add-channel')}}"><i class="fa fa-plus-circle"></i> Add channel</a>
            </div>
            <div class="add">
                <a href="javascript:void(0);" id="deleteAll" ><i class="fa fa-trash"></i> Remove All</a>
            </div>
            <header><h3 class="tabs_involved">Channel</h3></header>
			<table class="tablesorter1" cellspacing="0" cellpadding="0">
    			<thead>
    				<tr>
                        <th class="line_table center" ><input type="checkbox" name="selectAll" id="selectAll"></th>
        				<th style="min-width: 50px" class="line_table">ID</th>
        				<th class="line_table">Name</th>
        				<th class="line_table">Description</th>
        				<th class="line_table">Status</th>
        				<th class="no-show-sort" >Action</th>
    				</tr>
    			</thead>
    			<tbody>
                    @foreach ($channel as $result)
    				<tr>
                        <td class="line_table center check-item"><input type="checkbox" data-id="{{$result->id}}" name="check" id="check-{{$result->id}}"></td>
        				<td class="line_table">{{$result->id}}</td>
        				<td class="line_table">{{$result->title_name}}</td>
        				<td class="line_table">{!!str_limit($result->description,50)!!}</td>
        				<td class="line_table">
        					<?=($result->status==1)?'<span class="label label-success">Active</span>':''?>
        					<?=($result->status==0)?'<span class="label label-danger">Inactive</span>':''?>
        					<?=($result->status==3)?'<span data-toggle="tooltip" data-placement="top" title="Click here to approve" data-id="'.$result->ID.'" class="approve-channel1 label label-info pointer">Register</span>':''?>
        					<?=($result->status==2)?'<span class="label label-warning">Unpublish</span>':''?>
        				</td>
        				<td>
        					<a href="{{URL('admincp/edit-channel/')}}/{{$result->id}}"><i style="font-size: 20px ; margin: 5px" class="fa fa-pencil-square-o"></i></a>
        					<a href="{{URL('admincp/delete-channel/')}}/{{$result->id}}" onclick="return confirm('Are you sure remove Channel : {{$result->title_name}} ?')"><i style="font-size: 20px ; margin: 5px" class="fa fa-trash-o"></i></a>
        				</td>

    				</tr>
                    @endforeach
    			</tbody>
			</table>

			{{-- <div id="pager" class="pager">
				<form>
					<img src="{{URL('public/assets/css/table/first.png')}}" class="first"/>
					<img src="{{URL('public/assets/css/table/prev.png')}}" class="prev"/>
					<input type="text" readonly="readonly" class="pagedisplay"/>
					<img src="{{URL('public/assets/css/table/next.png')}}" class="next"/>
					<img src="{{URL('public/assets/css/table/last.png')}}" class="last"/>
					<select class="pagesize">
						<option   value="5">5</option>
						<option value="10">10</option>
						<option selected="selected" value="20">20</option>
						<option  value="30">30</option>
					</select>
				</form>
			</div> --}}

		</article><!-- end of content manager article -->



		<div class="spacer"></div>

<script type="text/javascript">
	$(document).ready(function(){
        $('.tablesorter1').DataTable({
            "columnDefs": [
                {
                    "targets": 0,
                    "orderable":false
                }
            ],
            'order': [1, 'asc'],
        });

        // Select all and delete all
        window.selectedId = [];
        $('.check-item input[type=checkbox]').prop('checked', false);

        // select all column
        $('#selectAll').click(function(){
            var checked = $("#selectAll").is(":checked");
            $('.check-item input[type=checkbox]').prop('checked', checked);
        });

        $("#deleteAll").click(function(){
            var conf = confirm('Are you sure you want to remove the channel?');
            if(!conf) return;

            var listIds = [];

            var getElementChecked = $('.check-item input[type=checkbox]:checked');
            var length = getElementChecked.length;

            $.each(getElementChecked, function(i, ele) {
                console.log(i,'i');
                var data = $(ele).data();
                if(data['id']) {
                    listIds.push(data['id']);
                }
                if(i == length - 1) {
                    if(listIds.length) {
                        deleteAllPornStars(listIds);
                    }
                }
            });
        });

        function deleteAllPornStars(ids) {
            $.ajax({
                method: "DELETE",
                url: "/admincp/channel/delete-ids",
                data: { ids : ids },
                headers: {
                   'X-CSRF-Token': '{{csrf_token()}}'
                }
            }).done(function( resp ) {
                if(resp.status) {
                    location.reload();
                }
            });
        }


        //
		$(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })
    	$(document).on('click','.approve-channel1',function(){

    		var data_id=$(this).attr('data-id');
    		$.ajax({
    			url:'{{URL()}}/admincp/approve-member-register-channel&id='+data_id,
    			success:function(data){

    				window.location.href="{{URL()}}/admincp/channel";
    			}
    		})
    	})
    });

</script>
@endsection