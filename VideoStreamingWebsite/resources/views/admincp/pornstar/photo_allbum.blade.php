@extends('admincp.master')
@section('title',"Pornstar Photo Gallery Manager ")
@section ('subtitle',"Pornstar Management")
@section('content')
@if(session('msg'))<h4 class="alert alert-success">{{ session('msg') }}</h4>@endif
@if(session('msgerro'))<div class="alert alert-error">{{session('msgerro')}}</div>@endif
		<article class="module width_full">
			<div class="add"><a href="{{URL('admincp/add-photo')}}/{{$porn_id}}"><i class="fa fa-plus-circle"></i> Add a photo to gallery</a></div>
		<header><h3 class="tabs_involved">{{$pornstar_photo->title_name}}'s Photo Gallery</h3></header>
			<table class="tablesorter" cellspacing="0" cellpadding="0"> 
			<thead> 
				<tr> 
    				<th class="line_table">Photo</th> 
    				<th >Action</th>
				</tr> 
			</thead> 
			<tbody> 
                @foreach ($photo_allbum as $result)
				<tr> 
    				<td class="line_table">
    					<img src="{{URL('public/upload/pornstar')}}/{{$result->photo}}" width="250" alt="">	
    				</td> 
    				<td><a href="{{URL('admincp/delete-photo/')}}/{{$result->id}}" onclick="return confirm('Are you sure you want to remove this ?')"><i style="font-size: 20px ; margin: 5px" class="fa fa-trash-o"></i></a></td>
    				
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


@endsection