<div id="main-message">
	<h2>Send message</h2>
	<div class="panel-body">
			<div class="message-Reply">
				<div id="msg" class="alert-error"></div>
					<div class="input-group">
						<input name="txt_message" style="border-radius: 0px" row="5" type="text" value="" class="form-control" placeholder="">
						<span class="input-group-btn">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<button id="send-to-member" message="" friendid="{{$memberid}}"  data-dismiss="modal" style="border-radius: 0px" class="btn btn-success" type="button">{{trans('home.SEND')}}</button>
						</span>
	                </div>
		 	</div> 
	</div>
</div>