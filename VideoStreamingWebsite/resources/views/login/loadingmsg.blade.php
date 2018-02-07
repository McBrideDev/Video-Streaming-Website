<?php
	$user=\Session::get('User');
	$getallmessage = \App\Models\MemberMessageModel::select('members.firstname','members.lastname','member_message.*')
						->where('member_message.frommember','=',$frommember)
						//->where('member_message.tomember','=',$frommember)
						->join('members','members.user_id','=','member_message.frommember')
						->get();
?>
	@foreach($getallmessage as $resultall)
		<div class="message-content">{{$resultall->firstname." ".$resultall->lastname}}: {{$resultall->message}} <span class="pull-right"><small>{{$resultall->created_at}}</small></span></div> 
	@endforeach