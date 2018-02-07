@extends('admincp.master')
@section('title',"Member's Video Upload")
@section('content')
<h4 class="alert_info">Welcome to Member's upload video administrator </h4>
		<article class="module width_full">
		<header>
			<h3 class="tabs_involved">All Video Upload</h3></header>
			<table class="tablesorter" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th style="min-width: 50px;" class="line_table">ID</th>
						<th class="line_table">Video Name</th>
						<th class="line_table">Post Author</th>
						<th class="line_table">Post Date</th>
						<th class="line_table">status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php $i=1; ?>
					@foreach($video as $result)
					<tr>
							<td class="line_table"><?=$i++;?></td>
							<td class="line_table">
								<div style="float: left; vertical-align: middle">
										{{str_limit($result->title_name,30)}}

								</div>
							 	<a  style="background-color: #FF4100;color: #fff; padding-left: 6px; padding-right: 6px; text-decoration: none" class="popup-video pull-right" href="{{$result->video_src}}"><i class="fa fa-play-circle"></i> Play Now </a><br/>
							</td>
							<td class="line_table">{{$result->firstname}} {{$result->lastname}}</td>
							<td class="line_table">
								<?php
								$date= new datetime($result->created_at);
								$format = $date->format('M d, H:i');
								echo $format;
								?>
							</td>
							<td class="line_table">
								<?php if($result->status==3) {?>
								<span id="change-status_{{$result->id}}"  ><span class="label label-info pointer">Waiting</span></span>
								<?php } if($result->status==1){?>
								<span id="change-status_{{$result->id}}" ><span class="label label-success pointer">Approved</span></span>
								<?php }if($result->status==5){?>
								<span id="change-status_{{$result->id}}"><span class="label label-danger pointer">Blocked</span></span>
								<?php }?>
							</td>
							<td class="align-center">
								<?php if($result->status!=3) {?>
								<?php if($result->status==5){?>
								<span class="btn-group">
									<a id="change-approve" class="pointer" data-id="{{$result->id}}" data-toggle="tooltip" data-placement="top" title="Approve" ><i class="fa fa-check"></i></a>
								</span>
								<?php }?>
								<?php if($result->status==1){?>
								<span class="btn-group">
									<a id="change-block" class="pointer" data-id="{{$result->id}}" data-toggle="tooltip" data-placement="top" title="Block" ><i class="fa fa-lock"></i></a>
								</span>
								<?php }?>
								<?php }?>
							</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			<div id="pager" class="pager">
				<form>
					<img src="{{URL('public/assets/css/table/first.png')}}" class="first"/>
					<img src="{{URL('public/assets/css/table/prev.png')}}" class="prev"/>
					<input type="text" readonly="readonly" class="pagedisplay"/>
					<img src="{{URL('public/assets/css/table/next.png')}}" class="next"/>
					<img src="{{URL('public/assets/css/table/last.png')}}" class="last"/>
					<select class="pagesize">
						<option selected="selected"  value="5">5</option>
						<option value="10">10</option>
						<option value="20">20</option>
						<option  value="30">30</option>
					</select>
				</form>
			</div>
		</article><!-- end of content manager article -->

		<div class="spacer"></div>
				<script type="text/javascript">
			$(document).ready(function() {
				$('.popup-video').magnificPopup({
					disableOn: 700,
					type: 'iframe',
					mainClass: 'mfp-fade',
					removalDelay: 160,
					preloader: false,

					fixedContentPos: false
				});
			});
			$(function () {
					$('[data-toggle="tooltip"]').tooltip()
				})
			$(document).on('click','#change-approve',function(){
					var data_id =$(this).attr('data-id');
					$.ajax({
						url:"approve&id="+data_id,
						success:function(data){
							$('#change-status_'+data_id+'').empty().append(data);
						}
					})
			})

			$(document).on('click','#change-block',function(){
					var data_id =$(this).attr('data-id');
					$.ajax({
						url:"block&id="+data_id,
						success:function(data){
							$('#change-status_'+data_id+'').empty().append(data);
						}
					})
			})
		</script>

@endsection
