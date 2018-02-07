<?php
use App\Helper\VideoHelper;
?>
@extends('master-frontend')
@section('title', 'Premium HD')
@section('content')
<div class="main-content">
  <div class="container-fluid top-rate ">
    <img src alt="">
    <div class="titile-cate">
      Premium HD Videos
      <ul>
        <li class="dropdown">
          <a href="#" class="hidden-xs dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><?= isset($filter_title_lg) ? '' . $filter_title_lg . '<span class="caret">' : ' Newest Videos <span class="caret">' ?></span></a>
          <a href="#" class="visible-xs dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><?= isset($filter_title_xs) ? '' . $filter_title_xs . '<span class="caret">' : ' Newest<span class="caret">' ?></a>
          <ul id="sort-premium" class="dropdown-menu">
            <li data-action="new-video" class="hidden-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-arrow-up"></i> Newest videos</a></li>
            <li data-action="new-video" data-name="" data-categories="" class="visible-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-arrow-up"></i> Newest</a></li>
            <li data-action="most-rated" data-name="" data-categories="" class="hidden-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-star"></i> Top rated videos</a></li>
            <li data-action="most-rated" data-name="" data-categories="" class="visible-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-star"></i> Rated</a></li>
            <li data-action="most-viewed" data-name="" data-categories="" class="hidden-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-line-chart"></i> Most viewed videos</a></li>
            <li data-action="most-viewed" data-name="" data-categories="" class="visible-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-line-chart"></i> Viewed</a></li>
            <!-- <li data-action="most-commented" data-name="" data-categories="" class="hidden-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-comments-o"></i> Most commented videos</a></li>
            <li data-action="most-commented" data-name="" data-categories="" class="visible-xs categories-sort"><a href="javascript:void(0)"><i class="fa fa-comments-o"></i> Commented</a></li> -->
          </ul>
        </li>
      </ul>
    </div>
    <div class="row content-image">
      <div class="col-sm-12 image-left">
        <div class="row videos">
          <?php $items = 1; ?>
          @foreach($premium as $key => $result)
          <?php $rating = VideoHelper::getRating($result->string_Id); ?>

          <div class="col-xs-6 col-sm-3 col-md-2 image-left">
            <div class="col">
              <div class="col_img">
                <span class="hd">HD</span>
                @if(\Session::has('User'))
                <?php
                $user = \Session::get('User');
                $user_id = $user->user_id;
                $check = VideoHelper::isPurchased($user_id, $result->string_Id);
                ?>
                @if (!empty($check))
                  <a href="{{URL(getLang().'watch')}}/{{$result->string_Id."/".$result->post_name}}.html">
                    <img data-preview ="{{$result->preview}}" data-src="{{$result->getImageUrl($result->poster)}}" class="js-videoThumbFlip img-responsive" data-digitsSuffix="{{$result->digitsSuffix}}" data-digitsPreffix="{{$result->digitsPreffix}}" data-from="{{$result->website}}" src="{{$result->getImageUrl($result->poster)}}" alt="{{$result->title_name}}" />
                    <!-- <button style="position: relative;top: -92px;left: 96px;" class="btn-success" type="">View Video</button> -->
                  </a>
                @else
                  <a href="#" @if(!\Session::has('User')) onclick="return showlogin();" @endif class="buy-video" role="<?php echo $result->string_Id ?>">
                     <img data-preview ="{{$result->preview}}" data-src="{{$result->getImageUrl($result->poster)}}" class="js-videoThumbFlip img-responsive" data-digitsSuffix="{{$result->digitsSuffix}}" data-digitsPreffix="{{$result->digitsPreffix}}" data-from="{{$result->website}}" src="{{$result->getImageUrl($result->poster)}}" alt="{{$result->title_name}}" />
                     <!-- <button role="<?php echo $result->string_Id ?>" style="position: relative;top: -92px;left: 96px;" class="btn-danger" type="">Buy Video</button> -->
                  </a>
                @endif

                @else
                <a href="#" onclick="return showlogin();">
                  <img data-preview ="{{$result->preview}}" data-src="{{$result->getImageUrl($result->poster)}}" class="js-videoThumbFlip img-responsive" data-digitsSuffix="{{$result->digitsSuffix}}" data-digitsPreffix="{{$result->digitsPreffix}}" data-from="{{$result->website}}" src="{{$result->getImageUrl($result->poster)}}" alt="{{$result->title_name}}"  />
                </a>

                @endif
                <div class="position_text">
                  <p class="time_minimute">{{sec2hms($result->duration)}}</p>
                </div>
              </div>
              @if(!empty($check))
              <h3>
                <a href="{{URL(getLang().'watch')}}/{{$result->string_Id."/".$result->post_name}}.html">{{$result->title_name}}</a>
              </h3>
              <span class="titleviews">{{$result->total_view ===NULL ? 0: $result->total_view}} {{trans('home.VIEWS')}}  <span class="titlerating"><i class="fa fa-thumbs-up" aria-hidden="true"></i> {{floor($rating['percent_like'])}}%</span></span>
              <h3 class="premium-buy"><a href="{{URL(getLang().'watch')}}/{{$result->string_Id.'/'.$result->post_name}}.html"><i class="fa fa-play"></i> Watch Now</a></h3>
              @else
              <h3><a href="#" @if(!\Session::has('User')) onclick="return showlogin()" @else class="buy-video" role="<?php echo $result->string_Id ?>" @endif>{{$result->title_name}} </a></h3>
              <span class="titleviews">{{$result->total_view ===NULL ? 0: truncate($result->total_view, 26) }} {{trans('home.VIEWS')}}  <span class="titlerating"><i class="fa fa-thumbs-up" aria-hidden="true"></i> {{floor($rating['percent_like'])}}%</span></span>
              <h3 role="<?php echo $result->string_Id ?>" class="premium-buy">
                <a href="#" @if(!\Session::has('User')) onclick="return showlogin()" @endif class="buy-video" role="<?php echo $result->string_Id ?>"><i class="fa fa-shopping-cart"></i> Buy this</a>
              </h3>
              @endif

            </div>
          </div>
          @if ($items == 2)
            <div class="col-xs-6 col-sm-4 col-md-3 image-right pull-right hidden-xs">
              <div class="ads-here-right">
                <p class="advertisement">ADVERTISEMENT</p>
                <?= StandardAdTopRate() ?>
              </div>
            </div>
            <div class="clearfix visible-xs"></div>
            <div class="col-sm-4 col-md-6 col-xs-6 image-left visible-xs">
              <div class="ads-here-right">
                <p class="advertisement">ADVERTISEMENT</p>
                <?= StandardAdTopRate() ?>
              </div>
            </div>
            <div class="clearfix visible-xs"></div>
          @endif
          @if ($items == 6)
          <div class="clearfix"></div>
          @endif
          <?php $items++; ?>
          @endforeach
        </div>
      </div>
      <!-- check payment -->
      <?php if (\Session::has('User')): ?>
        <?php
        $payment_config = GetPaymentConfig();
        $user = \Session::get('User');
        ?>

        <form id="pay-video" action='https://bill.ccbill.com/jpost/signup.cgi' method="POST">
          <input type=hidden name=clientAccnum value='{{$payment_config->clientAccnum}}'>
          <input type=hidden name=clientSubacc value='{{$payment_config->clientSubacc}}'>
          <input type=hidden name=formName value='{{$payment_config->form_signle}}'>
          <input type=hidden name=language value='{{$payment_config->language}}' >

          <input type=hidden name=allowedTypes value='{{$payment_config-> allowedTypes_signle}}' >
          <input type=hidden name=subscriptionTypeId value='{{$payment_config->subscriptionTypeId_signle}}' >
          <input type="hidden" name="formDigest" value="{{ csrf_token() }}">
          <input type="hidden" name="user_id" value="{{$user->user_id}}">
          <input type="hidden" id="video-id" name="video" value="">
          <input type="hidden" name="_token" value="{{ csrf_token()}}">
        </form>
      <?php endif ?>
    </div>
    <div class="page_navigation">
      {!!$premium->render()!!}
    </div>
  </div>
</div>
<script type="text/javascript">
  function showlogin() {
    $('#myModal').modal('show');
  }
  $(document).on('click', '.buy-video', function (event) {

    var id = $(this).attr('role');
    $('#video-id').val(id);
    $("#pay-video").submit();
    return false;
  });
</script>
@endsection
