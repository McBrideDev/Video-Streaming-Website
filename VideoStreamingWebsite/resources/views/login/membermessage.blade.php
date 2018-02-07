@if(isset($allmessage))
<div id="message-load-result">
	<table class="table table-striped">
		<thead> 
			<tr> 
				<th >#</th> 
				<th >{{trans('home.MESSAGE_FROM')}}</th> 
				<th >{{trans('home.RECEIVED_AT')}}</th>
				<th >{{trans('home.STATUS')}}</th>
				<th >{{trans('home.ACTION')}}</th>
			</tr> 
		</thead>
		<tbody>

			@if($allmessage->count() >0)
			<?php $i=1; ?>
			@foreach($allmessage as $result)
			<?php

			$countmsg =CountMessageMember($result->frommember);
			?>
			<tr>
				<td ><?php echo $i++;?></td>
				<td  title="{{$result->firstname." ".$result->lastname}} Send you {{$countmsg}} message"><a href="{{URL(getLang().'profile/')}}/{{$result->user_id}}/{{str_slug($result->firstname." ".$result->lastname)}}.html">{{$result->firstname." ".$result->lastname}} ({{$countmsg}})</a></td>
				<td >{{$result->created_at}}</td>
				<?php if($result->message_status==1){ ?>
					<td ><span class="label label-success">{{trans('home.NEW_MESSAGE')}}</span></td>
					<?php } if($result->message_status==2){?>
						<td ><span class="label label-info">{{trans('home.READED')}}</span></td>
						<?php } if($result->message_status==4){?>
							<td ><span class="label label-info">{{trans('home.REPLY')}}</span></td>
							<?php } if($result->message_status==3){?>
								<td ><span class="label label-danger">{{trans('home.BLOCK')}}</span></td>
								<?php } ?>

								<td >
									<a data-toggle="modal" href="javascript:void(0);" data-target="#{{$result->ID}}" href="#"><i class="glyphicon glyphicon-eye-open"></i></a>
									<a id="deletemessage"  href="{{$result->ID}}" style="cursor:pointer"><i class="glyphicon glyphicon-remove-sign"></i></a>
								</td>
							</tr>

							<div id="{{$result->ID}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="panel panel-primary">
										<div class="panel-heading">{{trans('home.MESSAGE_FROM')}} {{$result->firstname." ".$result->lastname}}</div>
										<div class="panel-body">
											<div id="box-message" class="box-message">
												<?php
												$user=\Session::get('User');
												$getallmessage = GetAllMessage($result->frommember);
												?>
												@foreach($getallmessage as $resultall)
												<div class="message-content">{{$resultall->firstname." ".$resultall->lastname}}: {{$resultall->message}} <span class="pull-right"><small>{{$resultall->created_at}}</small></span></div> 
												@endforeach
											</div>	
											<div class="message-Reply">
												<div class="input-group">
													<input name="reply-text" style="border-radius: 0px; background:#fff !important" type="text" value="" class="form-control" placeholder="Reply to {{$result->firstname." ".$result->lastname}} ">
													<span class="input-group-btn">
														<input type="hidden" name="_token" value="{{csrf_token()}}">
														<button id="send-reply" message="{{$result->ID}}" friend="{{$result->frommember}}"   style="border-radius: 0px" class="btn btn-signup " type="button">{{trans('home.REPLY')}}</button>
														<button data-dismiss="modal" style="border-radius: 0px" class="btn btn-signup margin-l" type="button">{{trans('home.CANCLE')}}</button>
													</span>
												</div>
											</div> 

										</div>
									</div>
								</div>
							</div>

							@endforeach
							@else
							<tr>
								<td style="color:#e39000; text-align: center;" colspan="5" rowspan="" headers="">{{trans('home.NO_MESSAGE')}}</td>
							</tr>	
							@endif

						</tbody> 

					</table>
					<div class="page_navigation">
						{!!$allmessage->render()!!}
						<!--  -->
					</div>
				</div>
				@endif