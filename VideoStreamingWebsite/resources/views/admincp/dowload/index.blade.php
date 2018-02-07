@extends('admincp.master')
@section('title',"Downloader")
@section('content')
<!-- <h4 class="alert_info">Welcome to video Upload administrator </h4> -->
    @if(session('success'))
        <div class="alert alert-success">{{session('success')}}</div>
    @endif
		<article class="module width_full">
			<div class="add"><a href="{{URL('admincp/dowload-video-add-view')}}"><i class="fa fa-plus-circle"></i> Add downloader </a></div>

		<header><h3 class="tabs_involved">Video Dowload</h3></header>

			<table class="tablesorter" cellspacing="0" cellpadding="0"> 
			<thead> 
				<tr> 
    				<th style="min-width: 50px;" class="line_table">ID</th> 
    				<th class="line_table">Video</th>
    				<th class="line_table">Categories</th>
    				<th class="line_table">Channel</th>
            <th class="line_table">Porn Star</th>
                    
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
                        {{$result->title_name}}
                         
                    </div>   
             <a  style="background-color: #FF4100;color: #fff; padding-left: 6px; padding-right: 6px; text-decoration: none" class="popup-video pull-right" href="{{$result->video_src}}"><i class="fa fa-play-circle"></i> Play Now </a><br/>       
            </td> 
    				<td class="line_table">{{$result->title_categories}}</td>
            <td class="line_table">{{$result->title_channel}}</td> 
            <td class="line_table">{{$result->title_porn}}</td>        
    				<td>
    					<a href="{{URL('admincp/edit-video/')}}/{{$result->string_Id}}"><i style="font-size: 20px ; margin: 5px" class="fa fa-pencil-square-o"></i></a>
    					<a href="{{URL('admincp/delete-video')}}/{{$result->string_Id}}" onclick="return confirm('Are you sure remove Video : {{$result->title_name}} ?')"><i style="font-size: 20px ; margin: 5px" class="fa fa-trash-o"></i></a>
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