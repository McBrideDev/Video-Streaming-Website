<?php
use App\Helper\PageHelper;
?>
<div id="jax-loading" style="" class="modal fade"
	data-keyboard="false"
	data-backdrop="static"
	tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<center><img width="32" height="32" src="{{URL('public/assets/images/loading_apple.gif')}}"/></center>
	</div>
</div>
<!-- end waring -->
<footer>
	<div class="container-footer">
		<div class="ads-here">
			<p class="advertisement">{{trans('home.ADVERTISEMENT')}}</p>
			<div class="ads-here-content"> <?=StandardAdFooter();?> </div>
		</div>
	</div>
	<div class="container-footer">
		<p class="tags text-center">{{trans('home.TAGS')}}</p>
		<div class="tags-here clear text-justify">
			<?php foreach ($tags as $result) { ?>
			<a class="btn btn-default btn-xs" role="button" href="{{URL(getLang().'search.html?keyword=')}}{{$result->tag}}">
				<span class="glyphicon glyphicon-tag"></span> {{$result->tag}}
			</a>
			<?php } ?>
		</div>
	</div>
	<div class="content-footer">
		<div class="container">
			<div class="row">
				<div class="col-xs-6 col-sm-4">
					<h2>{{trans('home.INFORMATIONS')}}</h2>
					<ul>
						<li><?= PageHelper::getStaticPageLink(1); ?></li>
						<li><?= PageHelper::getStaticPageLink(2); ?></li>
						<li><?= PageHelper::getStaticPageLink(3); ?></li>
						<li><?= PageHelper::getStaticPageLink(4); ?></li>
						<li>
							<a href="{{URL(getLang())}}/sitemap">{{trans('home.SITE_MAP')}}</a>
						</li>
					</ul>
				</div>
				<div class="col-xs-6 col-sm-4 no-border">
					<h2>{{trans('home.WORKING_WITH_US')}}</h2>
					<ul>
						<li><?= PageHelper::getStaticPageLink(6); ?></li>
						<li><?= PageHelper::getStaticPageLink(7); ?></li>
						<li><?= PageHelper::getStaticPageLink(8); ?></li>
						<li><a href="{{URL('admincp')}}">{{trans('home.WEBMASTERS')}}</a></li>
					</ul>
				</div>
				<div class="col-xs-6 col-sm-4">
					<h2>{{trans('home.HELP_SUPPORT')}}</h2>
					<ul>
						<li><a href="{{URL(getLang().'contact')}}">{{trans('home.CONTACT_US')}}</a></li>
						<li><a href="{{URL(getLang().'infomation-fqa')}}">{{trans('home.FAQs')}}</a></li>
						@foreach(PageHelper::getAnotherPage() as $page)
							<li><a href="{{URL(getLang().'information'.'/'.$page->id)}}">{{$page->titlename}}</a></li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="container-footer">
		<p class="copyright">
			{{$config->site_copyright}}
			<br> {{$config->site_address}}
			<br>{{$config->site_phone}}
		</p>
		<div class="icon-footer">
			<img src="{{URL('public/assets/images/copyright.jpg')}}" alt="image" />
		</div>
	</div>
</footer>
<script>
	$(".on-error-img").on("error", function(){
        $(this).attr('src', '/public/assets/images/no-image.jpg');
        // $(this).css('width', 'auto');
    });

    $(".on-error-banner").on("error", function(){
        $(this).attr('src', '/public/assets/images/noBanner.jpg');
        // $(this).css('width', 'auto');
    });
    window.baseDomain = '{{env("APP_DOMAIN", "174.138.79.240")}}';
</script>
@include('cookie.cookie')
@yield('scriptFooter')
