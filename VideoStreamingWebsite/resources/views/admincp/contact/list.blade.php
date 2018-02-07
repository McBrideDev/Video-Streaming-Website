@extends('admincp.master')
@section('title',"All Messages")
@section ('subtitle',"Administrators")
@section('content')
@if(session('msg-success'))<div class="alert alert-success"><span class="fa fa-check"></span><strong> {{session('msg-success')}}</strong></div>@endif
<article class="module width_full">
    <header><h3 class="tabs_involved">All Messages</h3></header>
    <table class="tablesorter" cellspacing="0" cellpadding="0"> 
        <thead> 
            <tr> 

                <th class="line_table">ID</th>
                <th class="line_table">Email</th>
                <th class="line_table">Name</th>
                <th class="line_table">Type</th>
                <th class="line_table">Created at</th>
                <th class="line_table">Status</th>
                <th>Action</th>
            </tr> 
        </thead> 
        <tbody>
            <?php $i=1; ?>
            @foreach($contact as $result)
            <tr> 

                <td class="line_table">{{$i++}}</td> 
                <td class="line_table">{{$result->email}}</td>
                <td class="line_table">{{$result->name}}</td>
                <td class="line_table">
                    <?php if($result->type=="bug"){ echo "Bug" ;}?>
                    <?php if($result->type=="feedback"){ echo "General Inquiries" ;}?>
                    <?php if($result->type=="verification"){ echo "User photo verification" ;}?>
                    <?php if($result->type=="amateur"){ echo "Amateur program" ;}?>
                    <?php if($result->type=="media"){ echo "Press/Media inquiry" ;}?>
                    <?php if($result->type=="content"){ echo "Content Removal Request" ;}?>
                    <?php if($result->type=="copyright"){ echo "Copyright Issue" ;}?>
                </td>

                <td class="line_table">{{$result->created_at}}</td>
                <td class="line_table">
                    <?=($result->status==1)? '<span class="label label-info">New</span>' :'<span class="label label-success">Replied</span>' ?>
                </td> 
                <td align="center">
                    <a href="javascript:void(0);" data-name="{{$result->name}}" data-id="{{$result->id}}" data-email="{{$result->email}}" data-content="{{$result->message}}" id="reply-contact" style="font-size: 18px;"><i class="fa fa-reply"></i></a>
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

<div id="msg-popup" class="modal fade" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="panel panel-primary">
            <form id="frm" action="" method="post" accept-charset="utf-8">
              <div class="panel-heading"></div>
              <div class="panel-body">

               <div class="form-group">
                <div class="col-md-2"><label class="label-control">Email</label></div>
                <div class="col-md-10"><label class="label-control" id="modal-email"></label></div>
            </div>
            <div class="form-group">
                <div class="col-md-2"><label class="label-control">Name</label></div>
                <div class="col-md-10"><label class="label-control" id="modal-name"></label></div>
            </div>
            <div class="form-group">
                <div class="col-md-2"><label class="label-control" >Message</label></div>
                <div class="col-md-10" id="modal-message"></div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <div class="col-md-2 pull-left"><label class="label-control" >Reply</label></div>
                <div class="col-md-12">
                    <textarea name="reply" class="form-control" rows="5"></textarea>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <input type="hidden" id="reply-id" name="id" value="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <center><button type="submit" name="send-reply" class="btn btn-info">Reply</button><button data-dismiss="modal" style="margin-left: 5px" name="close" class="btn btn-info">Close</button></center>
        </div>
    </form>
</div>
</div>
</div>
<script type="text/javascript">

$(document).ready(function() {

    $(document).on('click','#reply-contact',function(){
        var data_email = $(this).attr('data-email');
        var data_id = $(this).attr('data-id');
        var data_name = $(this).attr('data-name');
        var url='{{URL("")}}/admincp/contact&action=reply&id='+data_id;
        url = decodeURIComponent(url);
        url=url.replace("&amp;", "&");
        var data_message = $(this).attr('data-content');
        $('#reply-id').attr('value',data_id);
        $('#frm').attr('action',url);
        $('#modal-email').empty().append(data_email);
        $('#modal-name').empty().append(data_name);
        $('#modal-message').empty().append(data_message);
        $('#msg-popup').modal('show');
    })

});
</script>

@endsection