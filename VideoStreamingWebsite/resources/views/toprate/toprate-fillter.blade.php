<?php
use App\Helper\VideoHelper;
?>
<div class="content-image videos" >
    <?php $items=1;?>
    @if(count($toprate)>0)
    	@foreach($toprate as $result)
    	<?php $rating = VideoHelper::getRating($result->string_Id);?>
        <div class="col-xs-6 col-sm-3 col-md-2 image-left">
            <div class="col">
                <div class="col_img">
                    <span class="hd">HD</span>
                    <a href="{{URL(getLang().'watch')}}/{{$result->string_Id."/".$result->post_name}}.html">
                        <img data-preview ="{{$result->preview}}" data-src="{{$result->poster}}" class="js-videoThumbFlip img-responsive on-error-img" data-digitsSuffix="{{$result->digitsSuffix}}" data-digitsPreffix="{{$result->digitsPreffix}}" data-from="{{$result->website}}" src="{{$result->poster}}" alt="{{$result->title_name}}" />
                    </a>
                    <div class="position_text">
                        <p class="time_minimute">{{sec2hms($result->duration)}}</p>
                    </div>
                </div>
                <h3>
                    <a href="{{URL(getLang().'watch')}}/{{$result->string_Id."/".$result->post_name}}.html">{{$result->title_name}}</a>
                </h3>
                 <span class="titleviews">{{$result->total_view ===NULL ? 0: truncate($result->total_view, 26) }} {{trans('home.VIEWS')}}  <span class="titlerating"><i class="fa fa-thumbs-up" aria-hidden="true"></i> {{floor($rating['percent_like'])}}%</span></span>

            </div>
        </div>
        <?php if($items==4) {?>
        <div class="col-xs-6 col-sm-4 col-md-3 image-right pull-right">
            <div class="ads-here-right">
                <p class="advertisement">{{trans('home.ADVERTISEMENT')}}</p>
                <?=StandardAdTopRate();?>
            </div>
        </div>
        <?php }?>
        <?php if($items==8) {?><div class="clearfix"></div><?php }?>
        <?php $items++;?>
     	@endforeach
	@else
	<p style="text-indent: 15px;">{{trans('home.VIDEO_NOT_FOUND')}}</p>
	@endif
</div>
<div class="clearfix"></div>
<input type="hidden" data-date="<?=($date)? $date:'today' ?>" data-time="<?=($data_time)? $data_time: 'all' ?>" id="hiden-data-date-time" name="" value="">
<div id="page_toprate_fileter" class="page_navigation">
     {!!$toprate->render()!!}
</div>
@if(!empty($country_name) )
<script type="text/javascript">
    $('#txt-country').empty().text('{{$country_name->name}}');
    $('#country').attr('data-country','{{$country_name->id}}');
</script>
@else
<script type="text/javascript">
    $('#txt-country').empty().text('All')
    $('#country').attr('data-country','all')
</script>
@endif
@if(!empty($time) )
<script type="text/javascript">

    $('#txt-time').empty().text('{{$time}}');

</script>
@else
<script type="text/javascript">
    $('#txt-time').empty().text('All Time');
</script>
@endif

