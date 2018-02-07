<?php
use App\Helper\AppHelper;
$paymentConfig = AppHelper::getPaymentConfig();
?>

<div id="paymentDialog" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Payment Message</h4>
            </div>
            <form action='https://bill.ccbill.com/jpost/signup.cgi' method="get">
                <div class="modal-body">
                    Please buy this video for full view.
                    <div class="button-payment">
                        <?php
                        if (\Session::has('User')) {
                          $user = \Session::get('User');
                        }
                        ?>
                        <input type=hidden name=clientAccnum value='{{$paymentConfig->clientAccnum}}'>
                        <input type=hidden name=clientSubacc value='{{$paymentConfig->clientSubacc}}'>
                        <input type=hidden name=formName value='<?= isset($viewvideo) ? ($viewvideo->form_name != NULL) ? $viewvideo->form_name : $paymentConfig->form_signle : NULL ?>'>
                        <input type=hidden name=language value='{{$paymentConfig->language}}' >
                        <input type=hidden name=allowedTypes value='<?= isset($viewvideo) ? ($viewvideo->allowedTypes != NULL) ? $viewvideo->allowedTypes : $paymentConfig->allowedTypes_signle : NULL ?>' >
                        <input type=hidden name=subscriptionTypeId value='<?= isset($viewvideo) ? ($viewvideo->subscriptionTypeId != NULL) ? $viewvideo->subscriptionTypeId : $paymentConfig->subscriptionTypeId_signle : NULL ?>' >
                        <input type="hidden" name="formDigest" value="{{ csrf_token() }}">
                        <input type="hidden" name="user_id" value="{{isset($user)? $user->user_id : NULL }}">
                        <input type="hidden" name="video" value="{{isset($viewvideo)? $viewvideo->string_Id: NULL}}">
                        <input type="hidden" name="_token" value="{{ csrf_token()}}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Buy this</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
