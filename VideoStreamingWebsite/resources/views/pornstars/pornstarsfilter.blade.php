<?php if (isset($pornstar)): ?>
	<div class="col-xs-6 col-sm-12 col-md-12 col-lg-12 ">
		<div class="row content-image">
			<?php $items=1; ?>
			@foreach($pornstar as $key => $result)
			<div class="col-md-2 col-sm-3 col-xs-6 image-left">
				<div class="col">
					<div class="col_img">
						<img class="pornstar_img" src="{{$result->check_thumb($result->id)}}" alt="{{$result->title_name}}"  height="200" />
					</div>
					<h3 class="pornstar-title">
						<a class="text-capitalize" href="{{URL(getLang().'pornstars')}}/{{$result->id}}/{{$result->post_name}}">{{$result->title_name}}</a>
						<span>{{$result->getVideoByPornStarId($result->ID)}}</span>
					</h3>
				</div>
			</div>
			<?php if($items==2) {?>
			<div class="ol-sm-6 col-md-4 col-xs-6 image-right pull-right hidden-xs hidden-sm">
				<div class="ads-here-right porn-ads" >
					<p class="advertisement">{{trans('home.ADVERTISEMENT')}}</p>
					<?=StandardAdPornstar();?>
				</div>
			</div>
			<div class="clearfix visible-xs"></div>
			<div class="col-sm-6 image-left visible-xs">
				<div class="ads-here-right">
					<p class="advertisement">{{trans('home.ADVERTISEMENT')}}</p>
					<?=StandardAdPornstar()?>
				</div>
			</div>
			<div class="clearfix visible-xs"></div>
			<?php }?>
			<?php if($items==6) {?><div class="clearfix hidden-xs hidden-sm" style="margin-bottom: 15px"></div><?php }?>
				<?php $items++;?>
			@endforeach
		</div>
	</div>
	<div class="page_navigation">
		{!!$pornstar->render()!!}
	</div>
<?php else: ?>
	<h3>{{$msg_filter}}</h3>
<?php endif ?>
