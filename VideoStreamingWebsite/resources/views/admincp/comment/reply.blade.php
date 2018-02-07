@extends('admincp.master')
@section('title',"Reply to Video Comments")
@section ('subtitle',"Manage Comments")
@section('content')
<div class="row ">
    <div class="modal-dialog col-md-12" style="width:100% !important">
        <div class="panel panel-primary">
            <div class="panel-heading">Reply to the following comment</div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-2"><label class="label-control">Video name</label></div>
                    <div class="col-md-10"><label class="label-control"> {{$comment->title_name}}</label></div>
                </div>
                <div class="form-group">
                    <div class="col-md-2"><label class="label-control">Username</label></div>
                    <div class="col-md-10"><label class="label-control"> {{$comment->username}}</label></div>
                </div>
                <div class="form-group">
                    <div class="col-md-2"><label class="label-control">Comment</label></div>
                    <div class="col-md-10">{{$comment->post_comment}}</div>
                </div>


            </div>
        </div>

        <form action="{{URL('admincp/admin-reply-comment/')}}/{{$comment->id}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">

            <div class="col-sm-12">
                <div class="form-group">
                    <textarea name="post_comment" class="form-control" placeholder="Reply"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div align="center">
                        <button type="submit" value="Update" class="btn btn-primary">Update</button>
                        <a href="{{URL::to('/admincp/video-comment/')}}" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>




<div class="spacer"></div>


@endsection