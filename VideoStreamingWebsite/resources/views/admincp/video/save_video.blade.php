@extends('admincp.master')
@section('title',"Video")
@section('content')
    <div class="row">
        <div class="modal-dialog col-md-12" style="width:100% !important">
            <div class="panel panel-primary">
                <div class="panel-heading">Grab Video</div>
                <div class="panel-body">
                    
                    <form action="{{URL('admincp/save-video')}}" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="form-group">
                            <div class="col-md-2"><label class="label-control">Username</label></div>
                            <div class="col-md-10">
                                <?php if (\Session::has('logined')): ?>
                                    <?php 
                                        $user = \Session::get('logined');
                                     ?>
                                    <input type="text" name="username" class="form-control" value="<?php echo $user->username ?>" placeholder="Username">
                                <?php else: ?>
                                    <input type="text" name="username" class="form-control" value="" placeholder="Username">
                                <?php endif ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-2"><label class="label-control">Title</label></div>
                            <div class="col-md-10">
                                <input type="text" name="title" class="form-control" value="<?php echo !empty($data['title']) ? $data['title'] : '' ?>" placeholder="Title video ">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-2"><label class="label-control">Category</label></div>
                            <div class="col-md-10">
                                <?php 
                                    $categories = \App\Models\CategoriesModel::get();
                                 ?>
                                <select name="category" class="form-control">
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $key => $category): ?>
                                            <option value="<?php echo $category->ID ?>"><?php echo $category->title_name; ?></option>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-2"><label class="label-control">Tags</label></div>
                            <div class="col-md-10">
                                <!-- <input type="text" name="tags" class="form-control" value="" placeholder="Tags video "> -->
                                <textarea name="tags" class="form-control"><?php echo !empty($data['tag']) ? str_replace('  ', '', trim($data['tag'])) : '' ?>
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-2"><label class="label-control">Status</label></div>
                            <div class="col-md-10">
                                <select name="status" class="form-control">
                                    <option value="1">Public</option>
                                    <option value="0">Private</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-2"><label class="label-control">Link Url Video</label></div>
                            <div class="col-md-10">
                                <input type="text" name="link" class="form-control" value="<?php echo !empty($data['link']) ? $data['link'] : '' ?>" placeholder="Link url video ">
                                <input type="hidden" name="image" value="<?php echo !empty($data['image']) ? $data['image'] : '' ?>">
                                <input type="hidden" name="duration" value="<?php echo !empty($data['duration']) ? $data['duration'] : '' ?>">
                                <input type="hidden" name="description" value="<?php echo !empty($data['description']) ? $data['description'] : '' ?>">
                            </div>
                        </div>
                        <button type="submit" values="Publish" class="btn btn-info pull-right">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection