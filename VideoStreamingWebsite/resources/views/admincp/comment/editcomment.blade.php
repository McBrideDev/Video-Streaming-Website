@extends('admincp.master')
@section('title',"Edit Existing Video Comment")
@section ('subtitle',"Manage Comments")
@section('content')
<div class="row ">
                <div class="modal-dialog col-md-12" style="width:100% !important">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Edit Existing Video Comment</div>
                            <div class="panel-body">
                            <form action="{{URL('admincp/edit-video-comment/')}}/{{$edit->id}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                                     <div class="form-group">
                                        <div class="col-md-2"><label class="label-control">Video name</label></div>
                                        <div class="col-md-10"><label class="label-control"> {{$edit->title_name}}</label></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-2"><label class="label-control">Username</label></div>
                                        <div class="col-md-10"><label class="label-control"> {{$edit->username}}</label></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-2"><label class="label-control">Comment</label></div>
                                        <div class="col-md-10"><textarea class="form-control" name="post_comment" rows="12">{{$edit->post_comment}}</textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div align="center">
                                                <button type="submit" value="Update" class="btn btn-info">Update</button>
                                                   <a href="{{URL::to('/admincp/video-comment/')}}" class="btn btn-default">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>



<div class="spacer"></div>


@endsection