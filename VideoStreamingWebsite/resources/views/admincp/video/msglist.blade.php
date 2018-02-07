@extends('admincp.master')
@section('title',"Video")
@section('content')

		<article class="module width_full">
      @if(session('msg'))<div class="alert alert-success">{{session('msg')}}</div>@endif
      @if(session('msgerror'))<div class="alert alert-danger">{{session('msgerror')}}</div>@endif
		<header><h3 class="tabs_involved">Message Video</h3></header>

			<table class="tablesorter" cellspacing="0" cellpadding="0"> 
			<thead> 
				<tr> 
    				<th class="line_table">ID</th> 
    				<th class="line_table">Video Name</th>
    				<th class="line_table">Form email</th>
    				<th class="line_table">Message Content</th>
            <th class="line_table">Received at</th>
            <th class="line_table">Status</th>
            <th>Action</th>
				</tr> 
			</thead> 
			<tbody> 
        <?php $i=1; ?>
                @foreach($message as $result)
				<tr> 
    				<td class="line_table"><?=$i++;?></td> 
    				<td class="line_table"><a target="_new" href="{{URL()}}/{{$result->post_name}}.{{$result->string_Id}}.html"> #{{$result->string_Id}} / {{$result->title_name}}</a></td> 
    				<td class="line_table">{{$result->email}}</td>
            <td class="line_table">{{str_limit($result->content,20)}}</td> 
            <td class="line_table">
              <?php
              $date= new datetime($result->created_at);
              $format= $date->format('M d, H:i');
              echo $format;
              ?>
            </td>
            <td class="line_table"><?=($result->status==0)? "<span class='label label-info'>New message</span>" :"<span class='label label-success'>Replied</span>" ?></td>
    				<td>
    					<a href="{{URL('admincp/reply-message/')}}/{{$result->ID}}"><i style="font-size: 20px ; margin: 5px" class="fa fa-pencil-square-o"></i></a>
    					<a href="{{URL('admincp/delete-message')}}/{{$result->ID}}" onclick="return confirm('Are you sure you want to delete MessageID: {{$result->ID}} ?')"><i style="font-size: 20px ; margin: 5px" class="fa fa-trash-o"></i></a>
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
    </script>

@endsection