@extends('admincp.master')
@section('title',"Add Multiple Videos")
@section ('subtitle',"Video Management")
@section('content')
<div class="row ">
                <div class="modal-dialog col-md-12" style="width:100% !important">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Add Multiple Videos</div>
                            <div class="panel-body">
                <form id="postMulti" class="form-horizontal" enctype="multipart/form-data" accept-charset="utf-8">
                        <div class="form-group">
                            <div class="col-md-3"><label class="label-control">Select videos to upload</label></div>
                            <div class="col-md-9">

                                <div id="fileuploader"></div>

                                <script type="text/javascript">
                                        var uploadFiles = $("#fileuploader").uploadFile({
                                            url:"{{URL('admincp/auto-bulk-upload')}}",
                                            fileName:"myfile",
                                            allowedTypes:"mp4,mov,avi,flv",
                                            formData: [
                                                { name: '_token', value: $('meta[name="csrf-token"]').attr('content') },
                                                { name: 'string_id', value: $('input[name="string_id"]').attr('value') },
                                            ],
                                            multiple: true,
                                            autoSubmit:true,
                                            maxFileCount:20,
                                        });
                                </script>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3"><div id="loading-upload" class="pull-left"></div></div>
                        </div>
                    <input type="hidden" id="file_hidden" name="file_hidden" >
                    <input type="hidden" name="string_id" id="string_id" value="<?=mt_rand()?>">
                    <input type="hidden" id="fileupload" name="fileupload" >
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <center><a href="{{url('admincp/video')}}" title=""><input type="button" id="submit-upload" value="Save" class="btn btn-info"></a></center>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="spacer"></div>

<script type="text/javascript">
   
    $(document).ready(function(){
        $('#check_buy').click(function(){
            $('#show_ppv').slideToggle("fast");
        })
    })
</script>
@endsection