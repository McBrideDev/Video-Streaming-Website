@extends('admincp.master')
@section('title',"Payment")
@section('content')
@if(session('msg'))<div class="alert alert-success">{{session('msg')}}</div>@endif
<h4 class="alert_info">Welcome to payment subscription administrator </h4>

<article class="module width_full">
	<header><h3 class="tabs_involved">All Subscription</h3></header>
	<div class="panel-body">
		<table id="list-payment" class="display" cellspacing="0" cellpadding="0" searchable="false" style="border-bottom: 2px solid #ddd"> 
		<thead> 
			<tr> 
				<th class="line_table" style="border-bottom: 2px solid #ddd">ID</th> 
				<th class="line_table" style="border-bottom: 2px solid #ddd">Subscription name<input class="form-control search_input" type="text" onclick="stopPropagation(event);" placeholder="Search" /></th> 
				<th class="line_table" style="border-bottom: 2px solid #ddd">Customer's name<input class="form-control search_input" type="text" onclick="stopPropagation(event);" placeholder="Search" /></th> 
				<th class="line_table" style="border-bottom: 2px solid #ddd">Price</th>
				<th class="line_table" style="border-bottom: 2px solid #ddd">Description</th>
				<th class="line_table" style="border-bottom: 2px solid #ddd">Active Date</th>
				<th class="line_table" style="border-bottom: 2px solid #ddd">Status</th>
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
	    // Apply the filter
	    var searching = false;

	    $("#list-payment thead input").on('keyup change', function () {
	    	if(!searching){
	    		searching = true;
	    		table.column(1).search('');	    		
	    		table.column(2).search('');
	    		table
	            .column( $(this).parent().index())
	            .search( this.value )
	            .draw();	
	    	}	                
	    } );
	 	
	    // DataTable	    
		var table = $('#list-payment').DataTable( {
		    "pagingType": "full_numbers",
		    "serverSide": true,		
		    ajax: {
		        url: "{{URL('admincp/payment-manage')}}",
		        type: 'POST',
		        data:{
                         '_token':$('meta[name=csrf-token]').attr("content")
                    }
		    },	
	        "columnDefs": [
		        {
		            "targets": 0,
		            "data": "id",		            
		        },
		        {
		            "targets": 1,		            
		            "render":function(data,type,row){
		            	var _html = "";
		            	var url = "{{URL(getLang())}}";
		            	if(row.channel_id!=null){
		            		_html = "Channel : "+row.title_name+"<a href='"+url+"/channel/"+row.channel_id+"/"+row.title_name+"' data-title='"+row.title_name+"'> <i class='fa fa-external-link' aria-hidden='true'></i></a>";	
		            	}else{
		            		_html = "Video : "+row.title_name+"<a href='"+url+"/watch/"+row.video_id+"/"+row.video_slug+".html' data-title='"+row.title_name+"'> <i class='fa fa-external-link' aria-hidden='true'></i></a>";	
		            	}
		            	
		            	return _html;
		            },
		            "className": "clickable"	
		        },
		        {
		            "targets": 2,
		            "data": "customer_name",
		            "className": "clickable"	            
		        },
		        {
		            "targets": 3,
		            "render": function (data,type,row) {
	                    return row.subscriptionInitialPrice +" "+ row.subscriptionCurrency;
	                },		            
		        },
		        {
		            "targets": 4,
		            "data": "priceDescription",	            
		        },
		        {
		            "targets": 5,
		            "data": "timestamp",	            
		        },
		        {
		            "targets": 6,
		            "render": function (data,type,row) {		            	
		            	var _status = "";
		            	if(row.expired>=0){
							_status ='<span class="label label-success">Active</span>';
		            	}else{
		            		_status ='<span class="label label-danger">Inactive</span>';
		            	}
	                     return _status;
	                }		                            
		        }
	        ] ,
	        "drawCallback": function( settings ) {
		        searching = false;
		    }   
        
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
		})
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
