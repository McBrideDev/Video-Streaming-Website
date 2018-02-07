<div id="member-data-video" class="col-md-6">

    <table class="tablesorter">
        <thead> 
            <tr>
                <th colspan="2" class="line_table">Friend Of {{$member->firstname.' '.$member->lastname}}</th> 
            </tr> 
        </thead>
        <tbody>

            @if($friend->count() >0)
            <?php $i=1; ?>
            @foreach($friend as $result)
            <?php 
            $user=\Session::get('User');
            $member_name=GetMemberName($result->member_friend);
            ?>
            <tr>
                <td class="line_table">{{$i++}}</td>
                <td class="line_table">
                    @if($user->user_id == $result->member_friend )
                    <a href="{{URL(getLang().'member-proflie.html')}}"><img src="{{$member_name->CheckImageURL($member_name->avatar)}}" width="25" height="25" style="margin-bottom: 5px; border-radius: 10px;"> {{$member_name->firstname." ".$member_name->lastname}}</a>
                    @else
                    <a href="{{URL(getLang().'profile/')}}/{{$member_name->user_id}}/{{str_slug($member_name->firstname.' '.$member_name->lastname)}}.html"><img src="{{$member_name->CheckImageURL($member_name->avatar)}}" width="25" height="25" style="margin-bottom: 5px; border-radius: 10px;"> {{$member_name->firstname." ".$member_name->lastname}}</a>
                    @endif
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td style="color:#ec567b; text-align: center;" colspan="4" rowspan="" headers="">{{trans('home.YOU_DO_NOT_HAVE_FRIEND')}}</td>
            </tr>   
            @endif

        </tbody> 

    </table>
</div>