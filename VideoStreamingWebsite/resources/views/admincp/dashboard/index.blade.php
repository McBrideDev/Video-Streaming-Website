@extends('admincp.master')
@section('title',"Dashboard")
@section('content')
<h4 class="alert_info">Welcome to the Administration Dashboard  </h4>
<article class="module width_haf">
	<header><h3 class="tabs_involved">Video Statistics</h3></header>
	<table class="table_dashborad tablesorter" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th class="line_table">Summary</th>
				<th class="line_table">Results</th>

			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="line_table">Videos Awaiting Validation</td>
				<td class="line_table">{{$video_validation}}</td>

			</tr>
			<tr>
				<td class="line_table">Featured Videos</td>
				<td class="line_table">{{$video_featured}}</td>

			</tr>
			<tr>
				<td class="line_table">New Videos</td>
				<td class="line_table">{{$new_video}}</td>

			</tr>
			<tr>
				<td class="line_table">Total Videos</td>
				<td class="line_table">{{$total}}</td>
			</tr>
		</tbody>
	</table>

</article><!-- end of content manager article -->

<article class="module width_haf">
	<header><h3 class="tabs_involved">Comment Statistics</h3></header>
	<table class="table_dashborad tablesorter" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th class="line_table">Summary</th>
				<th class="line_table">Total Comment</th>

			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="line_table">Video Comments</td>
				<td class="line_table">{{$videocomment}}</td>

			</tr>
		</tbody>
	</table>

</article><!-- end of content manager article -->

<!-- <div class="clear"></div> -->

<article class="module width_haf">
	<header><h3 class="tabs_involved">Member Statistics</h3></header>
	<table class="table_dashborad  tablesorter" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th class="line_table">Summary</th>
				<th class="line_table">Results</th>

			</tr>
		</thead>
		<tbody>

			<tr>
				<td class="line_table">Total members</td>
				<td class="line_table">{{$membercount}}</td>

			</tr>
		</tbody>
	</table>

</article><!-- end of content manager article -->
<article class="module width_haf">
	<header><h3 class="tabs_involved">Categories Statistics</h3></header>
	<table class="table_dashborad tablesorter" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th class="line_table">Summary</th>
				<th class="line_table">Total Categories</th>

			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="line_table">Video Categories</td>
				<td class="line_table">{{$categories}}</td>

			</tr>

		</tbody>
	</table>

	</article><!-- end of content manager article -->
<!-- <div class="clear"></div>
 -->
<article class="module width_haf">
	<header><h3 class="tabs_involved">Message Statistics</h3></header>
	<table class="table_dashborad tablesorter" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th class="line_table">Summary</th>
				<th class="line_table">Total Message</th>

			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="line_table">Private Messages</td>
				<td class="line_table">{{$msgprivate}}</td>

			</tr>

		</tbody>
	</table>

</article><!-- end of content manager article -->
<article class="module width_haf">
	<header><h3 class="tabs_involved">Advertisement Statistics</h3></header>
	<table class="table_dashborad tablesorter" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th class="line_table">Summary</th>
				<th class="line_table">Total Ads</th>

			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="line_table">Standard Advertisement</td>
				<td class="line_table">{{$get_standard_ads}}</td>
			</tr>
			<tr>
				<td class="line_table">In-Video Text Advertisement</td>
				<td class="line_table">{{$get_text_ads}}</td>
			</tr>
			<tr>
				<td class="line_table">Videos Advertisement</td>
				<td class="line_table">{{$get_video_ads}}</td>
			</tr>

		</tbody>
	</table>

</article><!-- end of content manager article -->
<article class="module width_haf">
	<header><h3 class="tabs_involved">Last 5 Members Statistics</h3></header>
	<table class="table_dashborad tablesorter" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th class="line_table">Username</th>
				<th class="line_table">E-mail Address</th>

			</tr>
		</thead>
		<tbody>
			@if(count($member)>0)
			@foreach($member as $result)
			<tr>
				<td class="line_table">{{$result->firstname." ".$result->lastname}}</td>
				<td class="line_table">{{$result->email}}</td>

			</tr>
			@endforeach
			@endif

		</tbody>
	</table>

</article><!-- end of content manager article -->
<!-- <div class="clear"></div> -->
@endsection