@extends('admincp.master')
@section('title',"Payment Gateway Settings")\
@section ('subtitle',"Settings")
@section('content')
<div class="row ">
		<div class="modal-dialog col-md-12" style="width:100% !important">
		    @if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
			@if(session('msgerro'))<div class="alert alert-error">{{session('msgerro')}}</div>@endif
       		<div class="panel panel-primary">
               	<div class="panel-heading">Payment Gateway Configuration</div>
	                <div class="panel-body">
				<form action="{{URL('admincp/payment-setting')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				
			
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Client Account</label></div>
							<div class="col-md-10"><input class="form-control"  type="text" name="clientAccnum" value="{{$config->clientAccnum}}"></div>
						</div>

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Client SubAccount</label></div>
							<div class="col-md-10"><input class="form-control"  type="text" name="clientSubacc" value="{{$config->clientSubacc}}"></div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Form Name</label></div>
							<div class="col-md-10"><input class="form-control"  type="text" name="formName" value="{{$config->formName}}"></div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Form PPV</label></div>
							<div class="col-md-10"><input class="form-control"  type="text" name="form_signle" value="{{$config->form_signle}}"></div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Language Default</label></div>
							<div class="col-md-10">
								<select class="form-control" name="language">
									<option <?=($config->language=="English")? "selected='selected'" :""?> value="English">English</option>
									<option <?=($config->language=="Spanish")? "selected='selected'" :""?> value="Spanish">Español</option>
									<option <?=($config->language=="French")? "selected='selected'" :""?> value="French">Français</option>
									<option <?=($config->language=="German")? "selected='selected'" :""?> value="German">Deutsch</option>
									<option <?=($config->language=="Italian")? "selected='selected'" :""?> value="Italian">Italiano</option>
									<option <?=($config->language=="Japanese")? "selected='selected'" :""?> value="Japanese">日本語</option>
									<option <?=($config->language=="Korean")? "selected='selected'" :""?> value="Korean">한국어 언어</option>
									<option <?=($config->language=="Cantonese")? "selected='selected'" :""?> value="Cantonese">中國傳統 </option>
									<option <?=($config->language=="Mandarin")? "selected='selected'" :""?> value="Mandarin">中国人简化</option>
									<option <?=($config->language=="Portuguese_br")? "selected='selected'" :""?> value="Portuguese_br">Portuguese</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Allowed Types</label></div>
							<div class="col-md-10"><input class="form-control"  type="text" name="allowedTypes" value="{{$config->allowedTypes}}"></div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">Allowed Types PPV</label></div>
							<div class="col-md-10"><input class="form-control"  type="text" name="allowedTypes_signle" value="{{$config->allowedTypes_signle}}"></div>
						</div>
						<div class="form-group">
							<div class="col-md-2"><label class="control-label">subscription Type ID</label></div>
							<div class="col-md-10"><input class="form-control"  type="text" name="subscriptionTypeId" value="{{$config->subscriptionTypeId}}"></div>
						</div>

						<div class="form-group">
							<div class="col-md-2"><label class="control-label">subscription Type ID PPV</label></div>
							<div class="col-md-10"><input class="form-control"  type="text" name="subscriptionTypeId_signle" value="{{$config->subscriptionTypeId_signle}}"></div>
						</div> 
						<div class="form-group">
			
								<input type="hidden" name="id" value="{{$config->id}}">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
					
							<center><button type="submit" value="Publish" class="btn btn-info">Save</button></center>
						</div>

			</form>
		</article><!-- end of post new article -->
		
		
		
		<div class="spacer"></div>
		

@endsection