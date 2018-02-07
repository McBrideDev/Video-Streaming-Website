@extends('master-frontend')
@section('title', 'Infomation FAQ')
@section('content')

<div class="main-content">
    <div class="container">
        @if(isset($msglogin))
        {{$msglogin}}
        @else
        @endif

        <div class="row">
            <div id="col-md-12">
                <h2>{{trans('home.FAQs')}}</h2>
                <script type="text/javascript">
                  $(document).ready(function () {
                      $('#faqs h3').each(function () {
                          var tis = $(this), state = false, answer = tis.next('div').hide().css('height', 'auto').slideUp();
                          tis.click(function () {
                              state = !state;
                              answer.slideToggle(state);
                              tis.toggleClass('active', state);
                          });
                      });
                  });
                </script>
                <?= $fqa ?>
            </div>
        </div>
    </div>
</div>
@endsection