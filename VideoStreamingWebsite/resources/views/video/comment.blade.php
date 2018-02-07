
@if($result->comment_parent)
<div class="media comment-item" style="    margin-top: 45px;" data-comment-id="{{ $result->ID }}">
    @else
    <li class="media comment-item box" data-comment-id="{{ $result->ID }}">
        @endif
        <div class="pull-left">
        @if(\Session::has('User'))
            @if(\Session::get('User')->user_id==$result->member_Id)
            <a  href="{{URL(getLang().'member-proflie.html')}}">
                <?php if (file_exists(public_path() . '/upload/member/' . $result->avatar) == 0 or $result->avatar == NULL) { ?>
                    <img src="{{asset('public/assets/images/no-image.jpg')}}" class="com_img"  />
                <?php } else { ?>
                    <img src="{{asset('public/upload/member/')}}/{{$result->avatar}}" class="com_img">
                <?php } ?>
            </a>

            @else
            <a href="{{URL(getLang().'profile/')}}/{{$result->member_Id}}/{{str_slug($result->author->firstname.' '.$result->author->lastname)}}.html">
                <?php if (file_exists(public_path() . '/upload/member/' . $result->avatar) == 0 or $result->avatar == NULL) { ?>
                    <img src="{{asset('public/assets/images/no-image.jpg')}}" class="com_img"  />
                <?php } else { ?>
                    <img src="{{asset('public/upload/member/')}}/{{$result->avatar}}" class="com_img">
                <?php } ?>
            </a>

            @endif
        @else
            <a href="{{URL(getLang().'profile/')}}/{{$result->member_Id}}/{{str_slug($result->author->firstname.' '.$result->author->lastname)}}.html">
                <?php if (file_exists(public_path() . '/upload/member/' . $result->avatar) == 0 or $result->avatar == NULL) { ?>
                    <img src="{{asset('public/assets/images/no-image.jpg')}}" class="com_img"  />
                <?php } else { ?>
                    <img src="{{asset('public/upload/member/')}}/{{$result->avatar}}" class="com_img">
                <?php } ?>
            </a>
        @endif
        </div>
        <div class="media-body">

            @if(\Session::has('User'))
                @if(\Session::get('User')->user_id==$result->member_Id)
                <a href="{{URL(getLang().'member-proflie.html')}}"><span class="com_name">{{$result->author->firstname." ".$result->author->lastname}}</span> </a>
                @else
                <a href="{{URL(getLang().'profile/')}}/{{$result->member_Id}}/{{str_slug($result->author->firstname.' '.$result->author->lastname)}}.html"><span class="com_name">{{$result->author->firstname." ".$result->author->lastname}}</span> </a>
                @endif
            @else
            <a href="{{URL(getLang().'profile/')}}/{{$result->member_Id}}/{{str_slug($result->author->firstname.' '.$result->author->lastname)}}.html"><span class="com_name">{{$result->author->firstname." ".$result->author->lastname}}</span> </a>
            @endif

            <span class="com_date"><abbr class="timeago" title="<?php echo date('' . $result->created_at . ''); ?>"></abbr></span>
            <br />
            {{$result->post_comment}}
            @if($result->replies->count())
            {{ dumpComments($result->replies) }}
            @endif
        </div>
        @if($result->comment_parent)
</div>
@else
</li>
@endif
