@extends('admincp.master')
@section('title',"Manage an Existing Pornstar ")
@section ('subtitle',"Pornstar Management")
@section('content')
@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
@if(session('msgerro'))<div class="alert alert-error">{{session('msgerro')}}</div>@endif
		<article class="module width_full">
            <div class="add">
                <a href="{{URL('admincp/add-pornstar')}}"><i class="fa fa-plus-circle"></i> Add a new pornstar</a>
            </div>
            <div class="add">
                <a href="javascript:void(0);" id="deleteAll" ><i class="fa fa-trash"></i> Remove All</a>
            </div>
		<header><h3 class="tabs_involved">Listed Pornstars</h3></header>
			<table class="tablesorter1" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
                    <th class="line_table center" ><input type="checkbox" name="selectAll" id="selectAll"></th>
    				<th class="line_table">ID</th>
    				<th class="line_table">Name</th>
    				<th class="line_table">Description</th>
    				<th class="line_table">Status</th>
    				<th class="no-show-sort">Action</th>
				</tr>
			</thead>
			<tbody>
                @foreach ($pornstar as $result)
				<tr>
                    <td class="line_table center check-item"><input type="checkbox" data-id="{{$result->id}}" name="check" id="check-{{$result->id}}"></td>
    				<td class="line_table">{{$result->id}}</td>
    				<td class="line_table">{{$result->title_name}}</td>
    				<td class="line_table">{{str_limit($result->description,30)}}</td>
    				<td class="line_table">
    					<?=($result->status==1)?'<span class="label label-success">Active</span>':'<span class="label label-danger">Block</span>'?>
    				</td>
    				<td>
    					<a href="{{URL('admincp/edit-pornstar/')}}/{{$result->id}}"><i style="font-size: 20px ; margin: 5px" class="fa fa-pencil-square-o"></i></a>
    					<a href="{{URL('admincp/delete-pornstar/')}}/{{$result->id}}" onclick="return confirm('Are you sure remove Pornstar : {{$result->title_name}} ?')"><i style="font-size: 20px ; margin: 5px" class="fa fa-trash-o"></i></a>
    					<a href="{{URL('admincp/photo-pornstar/')}}/{{$result->id}}" ><i style="font-size: 20px ; margin: 5px" class="fa fa-photo"></i></a>
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
						<option  value="5">5</option>
						<option value="10">10</option>
						<option selected="selected" value="20">20</option>
						<option  value="30">30</option>
					</select>
				</form>
			</div> --}}
		</article><!-- end of content manager article -->



		<div class="spacer"></div>

        <script type="text/javascript">
            $('.tablesorter1').DataTable({
                "columnDefs": [
                    {
                        "targets": 0,
                        "orderable":false
                    }
                ],
                'order': [1, 'asc'],
            });
            $(document).ready(function() {
                window.selectedId = [];
                $('.check-item input[type=checkbox]').prop('checked', false);

                // select all column
                $('#selectAll').click(function(){
                    var checked = $("#selectAll").is(":checked");
                    $('.check-item input[type=checkbox]').prop('checked', checked);
                });

                $("#deleteAll").click(function(){
                    var conf = confirm('Are you sure you want to remove the pornstars?');
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
                            deleteAllPornStars(listIds);
                        }
                    });
                });

                function deleteAllPornStars(ids) {
                    console.log(ids,'ids');
                    $.ajax({
                        method: "DELETE",
                        url: "/admincp/porn-stars/delete-ids",
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
            });
        </script>
@endsection