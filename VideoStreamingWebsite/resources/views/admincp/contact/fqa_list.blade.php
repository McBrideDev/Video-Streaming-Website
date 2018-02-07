@extends('admincp.master')
@section('title',"FAQ")
@section ('subtitle',"Administrators")
@section('content')
@if(session('msg'))<div class="alert alert-success"><span class="fa fa-check"></span><strong> {{session('msg')}}</strong></div>@endif
<article class="module width_full">
    <div class="add"><a href="{{URL('admincp/add-faq')}}"><i class="fa fa-plus-circle"></i> Add FAQ</a></div>
    <header><h3 class="tabs_involved">FAQ</h3></header>
    <table class="tablesorter" cellspacing="0" cellpadding="0"> 
        <thead> 
            <tr> 

                <th class="line_table" style="min-width:40px">ID</th>
                <th class="line_table">Question</th>
                <th class="line_table">Answer</th>
                <th class="line_table">Status</th>
                <th>Action</th>
            </tr> 
        </thead> 
        <tbody>
            <?php $i=1; ?>
            @foreach($fqa as $result)
            <tr> 

                <td class="line_table">{{$i++}}</td> 
                <td class="line_table">{{str_limit($result->question,30)}}</td>
                <td class="line_table">{{str_limit($result->answer,40)}}</td>
                <td class="line_table">
                    <?=($result->status==1)? '<span class="label label-success">Active</span>':'<span class="label label-danger">Inactive</span>' ?>
                </td>
                <td align="center">
                    <a href="{{URL('admincp/edit-faq/')}}/{{$result->id}}"><i style="font-size: 20px ; margin: 5px" class="fa fa-pencil-square-o"></i></a>
                    <a href="{{URL('admincp/delete-faq/')}}/{{$result->id}}" onclick="return confirm('Are you sure you want to remove this ?')"><i style="font-size: 20px ; margin: 5px" class="fa fa-trash-o"></i></a>
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


@endsection