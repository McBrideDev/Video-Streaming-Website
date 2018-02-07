@if(isset($getmember) or isset($allmessage))
<div class="member-profile">
	<h2>{{trans('home.YOUR_PROFILE')}}</h2>
	<div class="row">
		<div class="col-md-3">
			<div class="list-group">
				<li class="list-group-item no-padding">
					<img class="img-responsive" src="{{$getmember->CheckImageURL($getmember->avatar)}}" alt="">
				</li>
       <a  id="messagemember" href="javascript:void(0);" style="cursor:pointer" class="list-group-item"><span class="badge">{{$countmessage}}</span>{{trans('home.MESSAGE')}}</a>
       <a href="javascript:void(0);" id="friendmember" class="list-group-item"><span class="badge">{{$countfriend}}</span>{{trans('home.FRIENDS')}}</a>
       <a href="javascript:void(0);" id="edit" class="list-group-item">{{trans('home.EDIT')}}</a>
     </div>
   </div>

   <div class="col-md-9">
     <div class="row profile-info">
      <div class="col-md-12">
       <!-- <div class="alert alert-info"></div> -->
       <h1 id="editname">{{$getmember->firstname." ".$getmember->lastname}} </h1>

       <dl class="dl-horizontal">
         <dt>{{trans('home.EMAIL')}}:</dt>
         <dd id="editemail">{{$getmember->email}}</dd>
         <dt>{{trans('home.ADDRESS')}}:</dt>
         <dd id="editaddress">{{$getmember->address}}</dd>
         <dt>{{trans('home.BIRTHDATE')}}:</dt>
         <dd id="editbirthdate">{{$getmember->birthdate}}</dd>
       </dl>

       <p id="editbio">{{$getmember->bio}}</p>
     </div>
   </div> <!-- /.row -->

   <div class="row">
    <div class="col-md-12">
     <div id="loadingmessges"></div>
     <div id="resultmessage">

      @include('login.membermessage')
      @include('login.memberfriend')
    </div>

  </div>
</div>
</div> <!-- /.col-md-9 -->

</div>
</div>
<script type="text/javascript">
	$(document).ready(function (){
		$(document).on('click','#deletemessage',function(e){
			e.preventDefault();
			var postid = $(this).attr('href');
			$.ajax({
				url: base_url+"delete-message/"+postid+".html",
				success: function (data) {
         $('#resultmessage').load('message-member.html');
        },beforeSend: function () {
         $('#setting-load').html("<img src='{{URL('public/assets/images/result_loading.gif')}}'/>").show();
        },complete: function (){
          $('#setting-load').html("<img src='{{URL('public/assets/images/result_loading.gif')}}'/>").hide();
     });
		});



   $(document).on('click','.page_navigation ul li a', function(e){
     e.preventDefault();

     var page= $(this).attr('href').split('page=')[1];
     getpagemessage(page);
   });
   function getpagemessage(page){
     $.ajax({
       url:base_url+"message-member.html?page="+page,
       success: function (data) {

         $('#resultmessage').empty().html(data)

       },
       beforeSend: function () {
         $('#loadingmessges').html("<img src='/public/assets/images/result_loading.gif'/>").show();
       },
       complete: function ()
       {
         $('#loadingmessges').html("<img src='/public/assets/images/result_loading.gif'/>").hide();
       }
     })
   }
 });
</script>
@endif
@if(isset($gettemp))
<div class="member-profile">
	<h2><center>{{trans('home.CHANGE_PASSWORD')}}</center></h2>
	<div class="row">
   <div class="col-md-6 col-md-offset-3">
    <div id="msg-change"></div>
    <form id="change-pass" class="form-horizontal" accept-charset="utf-8">
     <label>{{trans('home.OLD_PASSWORD')}}</label><small>({{trans('home.OLD_PASSWORD_COMMENT')}})</small>
     <input class="form-profile" type="password" id="currentpass" name="currentpass" value="" placeholder="">
     <label>{{trans('home.NEW_PASSWORD')}}</label>
     <input class="form-profile" type="password" id="newpass" name="newpass" value="" placeholder="">
     <label>{{trans('home.NEW_PASSWORD')}}</label><small>({{trans('home.YES_AGAIN')}})</small>
     <input class="form-profile" type="password" id="renewpass" name="newpass_confirmation" value="" placeholder="">
     <input type="hidden" name="_token" value="{{ csrf_token() }}">
     <center><input type="button" style="margin-top: 10px;"  id="save-new-pass" class="btn btn-signup" name="save" value="{{trans('home.SAVE')}}"></center>
   </form>
 </div>
</div>
</div>
<script type="text/javascript">
  $(document).on('click','#save-new-pass',function (e){
   e.preventDefault();
   $.ajax({
    url:base_url+"member-change-password.html",
    type:"post",
    data:{
     'currentpass':$('input[name=currentpass]').val(),
     'newpass':$('input[name=newpass]').val(),
     'newpass_confirmation':$('input[name=newpass_confirmation]').val(),
     '_token':$('input[name=_token]').val()
   },
   success:function(data){
     $('#myModal').modal('show');
   },
   beforeSend: function () {
    $('#setting-load').html("<img src='{{URL('public/assets/images/result_loading.gif')}}'/>").show();
  },
  complete: function (){
    $('#setting-load').html("<img src='{{URL('public/assets/images/result_loading.gif')}}'/>").hide();
  },error:function(data){
    $('#msg-change').empty().html('<div class="alert fade in alert-danger text-lowercase" ><i class="fa fa-times" data-dismiss="alert"></i>  '+data.responseJSON.message+'</div>').show();
  }

})

 });

</script>
@endif
@if(isset($transaction))
<div class="member-profile">
  <h2><center>{{trans('home.PAYMENT_HISTORY')}}</center></h2>
  <div class="row">
   <div class="col-md-12">
    <div id="msg-change"></div>
    <table class="table table-striped" cellspacing="0" width="100%" id="payment_history_table">
    <thead>
    <tr>
      <th class="txt-center">#</th>
      <th class="txt-center">Type</th>
      <th class="txt-center">Subcription name</th>
      <th class="txt-center">Price</th>
      <th class="txt-center">Date</th>
      </tr>
    </thead>
    <tbody>
    @foreach ($data as $key => $row)
      <tr>
      <?php
        $link ="";
        $url = URL(getLang());
        if(!is_null($row->channel_id)){
          $link = $url."/channel/".$row->channel_id."/".$row->title_name;
        }else{
          $link = $url."/watch/".$row->video_id."/".$row->video_slug.".html";
        }
      ?>
        <td>{{$key + 1}}</td>
        <td>{{!is_null($row->channel_id)?"Channel":"Video"}}</td>
        <td><a href="{{$link}}">{{$row->title_name}}</a></td>
        <td>{{$row->subscriptionInitialPrice.' '.$row->subscriptionCurrency}}</td>
        <td>{{$row->timestamp}}</td>
      </tr>
    @endforeach
    </tbody>
  </table>
 </div>
</div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
      console.log($("#payment_history_table").DataTable({
        "searching": false
      }
      ));
  } );
</script>
@endif