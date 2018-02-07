@extends('admincp.master')
@section('title',"Translator Language")
@section ('subtitle',"Language Management")
@section('content')
<div class="row ">
	<div class="modal-dialog col-md-12" style="width:100% !important">
		@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
		@if(session('msgerror'))<div class="alert alert-danger">{{session('msgerror')}}</div>@endif
		<div class="panel panel-primary">
			<div class="panel-heading">
				<span>Translator {{$language->languageName}} </span>
				<span style="float: right;"><a href="/admincp/language/stranlate/{{$language->id}}/add"><i class="fa fa-plus"></i></a></span>
			</div>
			<div class="panel-body">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Keyword</th>
							<th>Translator</th>
						</tr>
					</thead>
					<tbody>
					@foreach($lines as $key =>$result)
						<tr>
							<td class="info" data-key="{{$key}}" data-value="{{$result}}">{{$key}}</td>
							<td class="value-key"  data-key="{{$key}}" data-value="{{$result}}" >{{$result}}</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="spacer"></div>
<script type="text/javascript">
	$(document).ready(function(){
		$('.tablesorter1').DataTable();
		var insert = true,
		 keys = null,
		 elm = null,
		 oldValue = null,
		 oldKey = null;
		$(document).on('click','.value-key',function (e) {
			e.preventDefault();
        	e.stopPropagation();

			var value = $(this).attr('data-value'),
			key = $(this).data('key'),
			html = '<span id="'+key+'" class="input-group">'+
			'<input type="text" class="form-control '+key+'" name="stranlate" value="'+value+'" placeholder="">'+
				'<div class="input-group-btn" id="sizing-addon3">'+
					'<button href="" class="btn btn-info update-value"><i class="fa fa-check-square-o" aria-hidden="true"></i></button>'+
					'<button href="" class="btn btn-info cancle-value"><i class="fa fa-times" aria-hidden="true"></i></button>'+
				'</div>'+
			'</span>';

			if(insert===true){
				elm =$(this);
				oldValue = $(this).text();
        		$(this).text('');
				$(this).append(html)
			}
			keys = key;
			insert=false;

	    });

	    $(document).on('click','.update-value', function(e){
	    	if(keys!==null){

	    		var postValue =$('input.'+keys+' ').val();
	    		var SCRIPT_REGEX = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi;
				while (SCRIPT_REGEX.test(postValue)) {
				    postValue = postValue.replace(SCRIPT_REGEX, "");
				    $('#'+keys).children().val('')
				}
	    		var update = $.post(base_url+'/admincp/language/stranlate/{{$language->id}}',{_token: _token, dataValue: postValue , dataKey: keys })
	    		update.done(function(res){
	    			$('#'+keys).hide();
	    			$('#'+keys).children().val('')
	    			elm.append(res.dataValue);
	    			elm.attr('data-value', res.dataValue);
	    			elm.parent().find('.info').attr('data-value', res.dataValue);
	    			insert=true;
	    			keys =null;
	    		});
	    		update.fail(function(res){
	    			if(res.responseJSON.code === 422){
	    				elm.addClass('has-error');
	    				$('input.'+keys+' ').attr('placeholder', res.responseJSON.message)
	    			}

	    		});
	    	}
	    })
	    $(document).on('click','.cancle-value', function(e){
	    	e.preventDefault();
        	e.stopPropagation();
	    	if(keys!==null){
	    		$('#'+keys).children().val('')
	    		elm.text(oldValue);
		       	$('#'+keys).hide();
		       	insert=true;
	        }
	    })

	    // edit key translate
	    $(document).on('click','.info',function (e) {
			e.preventDefault();
        	e.stopPropagation();

        	var keyTrans = $(this).data('key');
        	var valTrans = $(this).data('value');

			var html = '<span id="'+keyTrans+'" class="input-group">'+
				'<input type="text" class="form-control key-'+keyTrans+'" name="stranlate" data-valuekey="'+valTrans+'" name="newKeyTrans" value="'+keyTrans+'" placeholder="">'+
				'<div class="input-group-btn" id="sizing-addon3">'+
					'<button href="" class="btn btn-info update-key"><i class="fa fa-check-square-o" aria-hidden="true"></i></button>'+
					'<button href="" class="btn btn-info cancel-key"><i class="fa fa-times" aria-hidden="true"></i></button>'+
				'</div>'+
			'</span>'+
			'<div class="err"></div>';

			if(insert===true){
				elm =$(this);
				oldKey = $(this).text();
        		$(this).text('');
				$(this).append(html)
			}
			insert=false;
	    });

	    $(document).on('click','.update-key', function(e){
	    	if(oldKey!==null){
	    		var postKey =$('input.key-'+oldKey+' ').val();
	    		var postValue =$('input.key-'+oldKey+' ').data('valuekey');

	    		var SCRIPT_REGEX = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi;
				while (SCRIPT_REGEX.test(postKey)) {
				    postKey = postKey.replace(SCRIPT_REGEX, "");
				    $('#'+oldKey).children().val('');
				}
	    		var update = $.post(base_url+'/admincp/language/stranlate/{{$language->id}}',{_token: _token, dataValue: postValue , dataKey: postKey, oldKey : oldKey });
	    		update.done(function(res){
	    			$('#'+oldKey).children().val('');
	    			elm.text(res.dataKey);
	    			elm.attr('data-key', res.dataKey);
	    			elm.parent().find('.value-key').attr('data-key', res.dataKey);
	    			insert=true;
	    			oldKey =null;
	    		});
	    		update.fail(function(res){
	    			if(res.responseJSON.code === 422){
	    				elm.addClass('has-error');
	    				$(elm).find('.err').empty();
	    				$(elm).find('.err').append('<span class="txt-err">'+res.responseJSON.message+'<span>');
	    			}

	    		});
	    	}
	    });

	    $(document).on('click','.cancel-key', function(e){
	    	e.preventDefault();
        	e.stopPropagation();
	    	if(oldKey!==null){
	    		$('#'+oldKey).children().val('');
	    		elm.text(oldKey);
		       	$('#'+oldKey).hide();
		       	insert=true;
	        }
	    });
	})
</script>
<style type="text/css" media="screen">
	.update-value, .cancle-value, .update-key, .cancel-key{
		font-size: 20px;
		position: relative;
		top: -5px;
		padding: 2px 10px;
		color: #fff !important;
		margin: 5px
	}
	.cancle-value, .cancel-key{
		left:-5px;
	}
</style>
@endsection