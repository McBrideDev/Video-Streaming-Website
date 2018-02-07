@extends('master-frontend')
@section('title','Member profile')
@section('content')
<div class="main-content">
    <div class="container">
        <!-- {{var_dump($checkfriend)}} -->
        <h2>{{$member->firstname.' '.$member->lastname}} Profile<span id="block-user" data-user="{{$member->user_id}}" class="pull-right pointer">{{trans('home.BLOCK_USER')}} <i class="glyphicon glyphicon-off"></i></span><span id="report-user" data-user="{{$member->user_id}}" class="pull-right pointer">{{trans('home.REPORT_USER')}} <i class="glyphicon glyphicon-thumbs-down"></i> </span></h2>
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <li class="list-group-item no-padding">
                        <a href="javascript:location.reload()">
                            <img class="img-responsive" src="/public/upload/member/{{$member->avatar}}" alt="">
                        </a>
                    </li>
                    <?php if ($checkfriend != NULL) { ?>
                      <?php if ($checkfriend->status == 1) { ?>
                        <a href="javascript:void(0);" id="un-friend-modal"  class="list-group-item"><i class="fa fa-user"></i> {{trans('home.FRIEND_WITH_YOU')}}</a>
                      <?php }if ($checkfriend->status == 0) { ?>
                        <a href="javascript:void(0);" class="list-group-item"><i class="fa fa-random"></i> {{trans('home.WAITING_APPROVE')}}</a>
                        <?php
                      }
                    }
                    ?>
                    <?php if ($checkfriend == NULL) { ?>
                      <a href="javascript:void(0);" id="add-friend"  	 class="list-group-item"><i class="fa fa-user-plus"></i> {{trans('home.ADD_FRIEND')}}</a>
                    <?php } ?>

                    <a href="javascript:void(0)" id="send-message-to-member" member-id ="{{$member->user_id}}" class="list-group-item">{{trans('home.SEND_MESSAGE')}}</a>
                    <a href="javascript:void(0)" id="view-member-video" member-id ="{{$member->id}}"  class="list-group-item">{{trans('home.VIDEOS')}}</a>
                    <!-- <a href="javascript:void(0)" id="view-member-playlist"  class="list-group-item">Playlist</a> -->
                    <a href="javascript:void(0)" id="view-member-subscribe" member-id ="{{$member->id}}" class="list-group-item">{{trans('home.SUBSCRIBE')}}</a>
                    <a href="javascript:void(0)" id="view-member-firend" member-id ="{{$member->user_id}}"   class="list-group-item">{{trans('home.FRIENDS')}}</a>
                </div>
            </div>

            <div class="col-md-9">
                <div class="row profile-info">
                    <div class="col-md-12">
                        <div id="member-load-result"></div>
                        <div id="member-view-result-profile">
                            <h1 id="editname">{{$member->firstname." ".$member->lastname}} </h1>

                            <dl class="dl-horizontal">
                                <dt>{{trans('home.EMAIL')}}:</dt>
                                <dd id="editemail">{{$member->email}}</dd>
                                <dt>{{trans('home.ADDRESS')}}:</dt>
                                <dd id="editaddress">{{$member->address}}</dd>
                                <dt>{{trans('home.BIRTHDATE')}}:</dt>
                                <dd id="editbirthdate">{{$member->birthdate}}</dd>
                            </dl>

                            <p id="editbio">{{$member->bio}}</p>
                            <!-- Comment -->
                            @if($member->is_comment==1)
                            <div class="clearfix"></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="comment">
                                        <script src="{{URL::asset('public/assets/js/jquery.timeago.js')}}" type="text/javascript" charset="utf-8"></script>
                                        <script type="text/javascript">
                                        $(document).ready(function () {
                                            $("abbr.timeago").timeago();
                                        });
                                        </script>

                                        <div class="input-group">
                                            <input name="profile-comment-text"  onkeydown="javascript:doEnter(event);" id="profile-comment-text" type="text" value="" class="form-control" placeholder="{{trans('home.ADD_COMMENT')}} !">
                                            <span class="input-group-btn">
                                                <input type="hidden" name="id" value="{{$member->user_id}}">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <button id="profile-send-comment" class="btn btn-signup" type="button">{{trans('home.ADD_COMMENT')}}</button>
                                            </span>
                                        </div><!-- /input-group -->

                                    </div>
                                    <ol  id="update-comment" class="timeline" style="padding-left: 0px">
                                        @foreach($get_comment as $result)
                                        <li class="box">
                                            <a href="{{URL(getLang().'profile/')}}/{{$result->member_Id}}/{{str_slug($result->firstname.' '.$result->lastname)}}.html">
                                                <?php if (file_exists(public_path() . '/upload/member/' . $result->avatar) == 0 or $result->avatar == NULL) { ?>
                                                  <img src="{{asset('public/assets/images/no-image.jpg')}}" class="com_img img-responsive"  />
                                                <?php } else { ?>
                                                  <img src="{{asset('public/upload/member/')}}/{{$result->avatar}}" class="com_img img-responsive">
                                                <?php } ?>
                                            </a>
                                            <a href="{{URL(getLang().'profile/')}}/{{$result->member_Id}}/{{str_slug($result->firstname.' '.$result->lastname)}}.html"><span class="com_name">{{$result->firstname." ".$result->lastname}}</span> </a>

                                            <span class="com_date"><abbr class="timeago" title="<?php echo date('' . $result->created_at . ''); ?>"></abbr></span>
                                            <br />
                                            {{$result->post_comment}}
                                        </li>
                                        @endforeach
                                    </ol>
                                    <!--  -->
                                </div>
                            </div>
                            @endif
                            <!-- end Comment -->
                        </div>

                    </div>
                </div> <!-- /.row -->

            </div> <!-- /.col-md-9 -->

        </div>
    </div>
</div>
<!-- Add Friend user Modal -->
<div id="addmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="panel panel-primary">
            <div class="panel-heading">{{trans('home.ADD')}} {{$member->firstname.' '.$member->lastname}} {{trans('home.AS_A_FRIEND')}}</div>
            <div class="panel-body">
                <div id="msg-return" class="msg-modal">{{trans('home.ARE_YOU_SURE_YOU_WANT_TO_ADD')}} {{$member->firstname.' '.$member->lastname}} {{trans('home.AS_A_FRIEND')}} ?</div>
            </div>
            <div class="panel-footer">
                <div class="input-group" style="width: 100%;">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="button" id="save-friend" data="{{$member->user_id}}"  value="Add Friend" class="btn btn-signup pull-right">
                    <input type="button" data-dismiss="modal" onclick="javascript:location.reload()" id="cancel" class="btn btn-signup pull-right" style="margin-right: 5px;" value="{{trans('home.CLOSE')}}">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Unfriend user Modal -->
<div id="unmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="panel panel-primary">
            <div class="panel-heading">Un Friend With {{$member->firstname.' '.$member->lastname}}</div>
            <div class="panel-body">
                <div id="msg-unfriend" class="msg-modal">{{trans('home.ARE_YOU_SURE_YOU_WANT_TO_UN_FRIEND_WITH')}} {{$member->firstname.' '.$member->lastname}}	?</div>
            </div>
            <div class="panel-footer">
                <div class="input-group" style="width: 100%;">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="button" id="un-friend" data="{{$member->user_id}}"  value="UnFriend" class="btn btn-signup pull-right">

                    <input type="button" data-dismiss="modal" onclick="javascript:location.reload()" id="cancel" class="btn btn-signup pull-right" style="margin-right: 5px;" value="{{trans('home.CLOSE')}}">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Block user Modal -->
<div id="block-user-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="panel panel-primary">
            <div class="panel-heading">{{trans('home.PLEASE_TELL_ME_WHY_YOU_BLOCK')}} {{$member->firstname.' '.$member->lastname}}</div>
            <div id="" class="panel-body">
                <div id="msg-block" class="msg-modal"></div>
                <textarea name="block_content" row="5"  class="form-control"></textarea>
            </div>
            <div class="panel-footer">
                <div class="input-group" style="width: 100%;">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="button" id="block-user-send" data="{{$member->user_id}}"  value="{{trans('home.BLOCK')}}" class="btn btn-signup pull-right">
                    <input type="button" data-dismiss="modal"  id="cancel" class="btn btn-signup pull-right" style="margin-right: 5px;" value="{{trans('home.CLOSE')}}">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Report user Modal -->
<div id="report-user-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="panel panel-primary">
            <div class="panel-heading">{{trans('home.PLEASE_TELL_ME_WHY_YOU_REPORT')}} {{$member->firstname.' '.$member->lastname}}</div>
            <div class="panel-body ">
                <div id="msg-report" class="msg-modal"></div>
                <textarea name="report_content" row="5"  class="form-control"></textarea>
            </div>
            <div class="panel-footer">
                <div class="input-group" style="width: 100%;">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="button" id="report-user-send" data="{{$member->user_id}}"  value="{{trans('home.SEND')}}" class="btn btn-signup pull-right">
                    <input type="button" data-dismiss="modal"  id="cancel" class="btn btn-signup pull-right" style="margin-right: 5px;" value="Close">
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  $(document).ready(function () {

      $(document).on('click', '#save-friend', function (e) {
          e.preventDefault();
          var member = $(this).attr('data');
          $.ajax({
              url: "{{URL(getLang().'add-friend.html')}}",
              type: "POST",
              data: {
                  'id': member,
                  '_token': $('input[name=_token]').val()
              },
              success: function (data) {
                  $('#msg-return').empty().append(data);
              }
          })
      });

      $(document).on('click', '#un-friend', function (e) {
          e.preventDefault();
          var member = $(this).attr('data');
          $.ajax({
              url: "{{URL(getLang().'un-friend.html')}}",
              type: "POST",
              data: {
                  'id': member,
                  '_token': $('input[name=_token]').val()
              },
              success: function (data) {
                  $('#msg-unfriend').empty().append(data);
              }
          })
      })


  });
  function doEnter(evt)
  {
      if (evt.keycode == 13 || evt.which == 13)
      {
          $.ajax({
              url: "{{URL(getLang().'profile-comment')}}",
              type: "POST",
              data: {
                  'profile_comment_text': $('input[name=profile-comment-text]').val(),
                  'profileid': $('input[name=id]').val(),
                  '_token': $('input[name=_token]').val()
              }, success: function (data) {
                  $('#update-comment').prepend(data);
                  $('input[name=profile-comment-text]').val("");
              }
          });
      }
  }
</script>
@endsection