@extends('admincp.master')
@section('title',"Header News")
@section('content')

		<article class="module width_full">
      @if(session('msg'))<div class="alert alert-success">{{session('msg')}}</div>@endif
      @if(session('msgerror'))<div class="alert alert-danger">{{session('msgerror')}}</div>@endif
      <div class="add"><a href="{{URL('admincp/add-header-link')}}"><i class="fa fa-plus-circle"></i> Add Header Link</a></div>
		<header><h3 class="tabs_involved">Header link</h3></header>

			<table class="tablesorter" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
    				<th class="line_table">ID</th>
    				<th class="line_table">Name</th>
    				<th class="line_table">Content</th>
    				<th class="line_table">Link</th>
            <th class="line_table">Created At</th>
            <th class="line_table">Status</th>
            <th>Action</th>
				</tr>
			</thead>
			<tbody>
        <?php $i=1; ?>
                @foreach($header_link as $result)
				<tr>
    				<td class="line_table"><?=$i++;?></td>
    				<td class="line_table"><?=$result->title_name ?></td>
            <td class="line_table"><?=str_limit($result->content,30) ?></td>
    				<td class="line_table"><?=$result->link?></td>
            <td class="line_table">
              <?php
              $date= new datetime($result->created_at);
              $format= $date->format('M d, H:i');
              echo $format;
              ?>
            </td>
            <td class="line_table">
              <?=($result->status==1)? "<span class='label label-success'>Active</span>":"<span class='label label-danger'>Hidden</span>" ?>
            </td>
    				<td>
    					<a href="{{URL('admincp/edit-header-link/')}}/{{$result->id}}"><i style="font-size: 20px ; margin: 5px" class="fa fa-pencil-square-o"></i></a>
    					<a href="{{URL('admincp/delete-header-link/')}}/{{$result->id}}" onclick="return confirm('Are you sure remove messageID: {{$result->id}} ?')"><i style="font-size: 20px ; margin: 5px" class="fa fa-trash-o"></i></a>
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