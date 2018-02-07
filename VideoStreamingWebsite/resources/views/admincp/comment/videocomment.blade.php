@extends('admincp.master')
@section('title',"Manage Video Comments")
@section ('subtitle',"Manage Comments")
@section('content')
@if(session('msg-success'))<div class="alert alert-success"><span class="fa fa-check"></span><strong> {{session('msg-success')}}</strong></div>@endif
<article class="module width_full">
    <header><h3 class="tabs_involved">Video Comments</h3></header>
    <table class="tablesorter1 table table-hover" cellspacing="0" cellpadding="0"> 
        <thead> 
            <tr> 

                <!-- <th class="line_table">ID</th> -->
                <th class="line_table">Video Title</th>
                <th class="line_table">Comment</th>
                <th class="line_table">Username</th>
                <th class="line_table">Created On</th>
                <th>Action</th>
            </tr> 
        </thead> 
        <tbody> 
            <?php $i = 1; ?>
            @if(isset($comment))
            @foreach($comment  as $result)
            <?php
            $countcomment = \App\Models\VideoCommentModel::where('video_Id', '=', $result->video_Id)->count();
            ?>
            <tr> 

                <!-- <td class="line_table">
                    <div class="video_id"><b>#{{$result->ID}}</b></div> 
                    <div align="center"><img class="img-thumbnail" src="{{$result->poster}}"></div>            
                </td> --> 
                <td class="line_table">{{$result->title_name}}</td>
                 <td class="line_table">{{str_limit($result->post_comment,30)}}</td>
                  <td class="line_table">{{$result->username}}</td>
                <td class="line_table">{{$result->created_at}}</td> 
                <td>
                    <a href="{{URL::to('/admincp/edit-video-comment/')}}/{{$result->id}}"><i  class="fa fa-pencil-square-o"></i></a>
                    <a href="{{URL::to('/admincp/delete-video-comment/')}}/{{$result->id}}" onclick="return confirm('Are you sure remove this comment ?')"><i  class="fa fa-trash-o"></i></a>
                    <a href="{{URL::to('/admincp/admin-reply-comment/')}}/{{$result->id}}"><i  class="fa fa-comment-o "></i></a>
                </td>

            </tr> 
            @endforeach
            @endif
            @if(isset($msg))
            <tr>
                <td align="center" colspan="6">No comments available</td>
            </tr>
            @endif
        </tbody> 
    </table>
</article><!-- end of content manager article -->



<div class="spacer"></div>

<script type="text/javascript">

    $(document).ready(function() {
    $('.tablesorter1').DataTable();
// $("#checkall").click(function(){
//     var status=this.checked;
//     $("input[name='check']").each(function(){this.checked=status;})
// });

        $("#report-comment").click(function() {
            var listid = "";
            $("input[name='check']").each(function() {
                if (this.checked)
                    listid = listid + "," + this.value;
            })
            listid = listid.substr(1);     //alert(listid);
            if (listid == "") {
                alert("Please check item on list !");
                return false;
            }
            getaction = confirm("Are you sure report this comment ?");
            if (getaction == true)
                document.location = "{{URL('admincp/report-comment&id=')}}" + listid;
        });
    });
</script>

@endsection