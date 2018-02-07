@extends('admincp.master')
@section('title',"Video")
@section('content')
<div class="row ">
    <div class="modal-dialog col-md-12" style="width:100% !important">
        <div class="panel panel-primary">
            <div class="panel-heading">Reply Message</div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-2"><label class="label-control">Form</label></div>
                    <div class="col-md-10"><label class="label-control">{{$reply->email}}</label></div>
                </div>
                <div class="form-group">
                    <div class="col-md-2"><label class="label-control">Content</label></div>
                    <div class="col-md-10">{{$reply->content}}</div>
                </div>


            </div>
        </div>

        <form action="{{URL('admincp/reply-message/')}}/{{$reply->ID}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <h3>Leave a Reply</h3>
            <div class="col-sm-12">
                <div class="form-group">
                    <textarea name="content_reply" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <input type="hidden" name="id" value="{{$reply->ID}}">
                    <input type="hidden" name="email" value="{{$reply->email}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="pull-right">
                        <button type="submit" value="Update" class="btn btn-primary">Send</button>
                        <a href="{{URL::to('/admincp/private-message')}}" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>




<div class="spacer"></div>


@endsection