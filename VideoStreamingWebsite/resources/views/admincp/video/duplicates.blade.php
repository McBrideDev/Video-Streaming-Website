@extends('admincp.master')
@section('title',"Duplicate existing videos")
@section ('subtitle',"Duplicate videos")
@section('content')
<link rel="stylesheet" type="text/css" href="{{URL::asset('public/assets/css/bootstrap-table.css')}}">
<script src="{{URL::asset('public/assets/js/bootstrap-table.js')}}"></script>

<article class="module width_full panel-primary">
	<!-- <header><h3 class="tabs_involved">Video Management</h3></header> -->
	<div class="panel-heading">Duplicate existing videos
		<span class="pull-right"> <a class="btn-organ" id="deleteAll" href="javascript:void(0)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete Videos </a></span>
	</div>
	<div class="panel-body">
		<table id="list-videos" class="display" cellspacing="0" cellpadding="0" searchable="false" style="border-bottom: 2px solid #ddd">
		<thead>
			<tr>
				<th class="line_table" style="border-bottom: 2px solid #ddd"><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
				<th class="line_table" style="border-bottom: 2px solid #ddd">Video Title</th>
				<th class="line_table" style="border-bottom: 2px solid #ddd">Duplicate</th>
				<th class="line_table" style="border-bottom: 2px solid #ddd">Action</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
		</table>
	</div>
</article><!-- end of content manager article -->

<div class="spacer"></div>

<script type="text/javascript">
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})

	$(document).ready(function() {

		// DataTable
		var table = $('#list-videos').DataTable( {
			"pagingType": "full_numbers",
			"serverSide": true,
			"ajax": {
				url: "{{URL('admincp/duplicates-list-video')}}",
				type: 'GET',
				data: {
					'_token':$('meta[name=csrf-token]').attr("content")
				}
			},
			"columnDefs": [
				{
					"data": "string_Id",
					"targets": 0,
					"searchable":false,
					"orderable":false,
					"align": "center",
					"className": "dt-body-center",
					"render": function ( data, type, row ) {
						return '<input type="checkbox" name="id[]" value="'
							+ $('<div/>').text(data).html() + '">';
					}
				},
				{
					"targets": 1,
					"data": "title_name",
					"sortable": true,
					"editable": true,
					"align": 'left'
				},
				{
					"targets": 2,
					"data": "total_duplicaties",
					"sortable": true,
					"align": 'center'
				},
				{
					"targets": 3,
					"align": 'center',
					"render":function(data,type,row){
						var _html = "";
						var url = "{{URL('admincp/')}}";

						_html = "<a href='"+url+"/delete-duplicates-video/"+row.string_Id+"' onclick=\"return confirm('Are you sure remove Video :" + row.title_name +"?')\"> <i style='font-size: 20px ; margin: 5px' class='fa fa-trash-o'></i></a>";

						return _html;
					},
					"className": "clickable",
					"orderable":false
				}
			],
			'order': [1, 'asc']
		});
		$(".dataTables_filter").hide();

		// cellclick even
		table.on('click', 'tbody td', function(e) {
			if(this.cellIndex==1||this.cellIndex==2){
				if($(e.target).hasClass('fa')){
					return;
				}
				$('.search_input').val('');
				var title = table.column( this.cellIndex ).header();
				if(this.cellIndex==1)
					$(title).find('input').val($(this).find('a').data('title'));
				else{
					$(title).find('input').val(this.textContent);
				}
				$(title).find('input').trigger('keyup');
			}
		});

		// Handle click on "Select all" control
		$('#example-select-all').on('click', function(){
			// Check/uncheck all checkboxes in the table
			var rows = table.rows({ 'search': 'applied' }).nodes();
			$('input[type="checkbox"]', rows).prop('checked', this.checked);
		});

		// Handle click on checkbox to set state of "Select all" control
		$('#list-videos tbody').on('change', 'input[type="checkbox"]', function(){
			// If checkbox is not checked
			if(!this.checked){
			   var el = $('#example-select-all').get(0);
			   // If "Select all" control is checked and has 'indeterminate' property
			   if(el && el.checked && ('indeterminate' in el)){
				  // Set visual state of "Select all" control
				  // as 'indeterminate'
				  el.indeterminate = true;
			   }
			}
		});

		$("#deleteAll").click(function () {
			var listid = "";
			$("input[name='id[]']").each(function () {
				if (this.checked)
				listid = listid + "," + this.value;
			})
			listid = listid.substr(1);   //alert(listid);
			if (listid == "") {
				alert("Please check item on list !");
				return false;
			}
			getaction = confirm("Are you sure you want to delete all checks?");
			if (getaction == true)
				document.location = "{{URL('admincp/delete-duplicates-video')}}/" + listid;
		});
	});
	function stopPropagation(evt) {
		if (evt.stopPropagation !== undefined) {
			evt.stopPropagation();
		} else {
			evt.cancelBubble = true;
		}
		$(this).keydown(function(e){
			if (e.keyCode == 65 && e.ctrlKey) {
				e.target.select()
			}

		})
	}

</script>

@endsection
