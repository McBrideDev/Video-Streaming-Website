<?php
use App\Helper\AppHelper;

$config = AppHelper::getSiteConfig();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>@yield('title') | {{$config->site_name}}</title>
		<meta name="description" content="{{$config->site_description}}"/>
		<meta name="keywords" content="{{$config->site_keyword}}"/>
		<meta property="og:url"           content="{{URL()}}" />
		<meta property="og:type"          content="{{$config->site_name}}" />
		<meta property="og:title"         content="@yield('title')" />
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<link rel="stylesheet" href="{{URL::asset('public/assets/css/layout.css')}}?v=1" type="text/css" media="screen" />
		<link rel="stylesheet" href="{{URL::asset('public/assets/font-awesome-4.3.0/css/font-awesome.min.css')}}">
		<link href="{{URL::asset('public/assets/css/uploadify.css')}}" rel='stylesheet'>
		<link href="{{URL::asset('public/assets/css/magnific-popup.css')}}" rel='stylesheet'>
		<link rel="stylesheet" href="{{URL::asset('public/assets/font-awesome-4.3.0/css/font-awesome.min.css')}}">
		<link href="{{URL('public/assets/css/wysiwyg-color.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{URL('public/assets/css/bootstrap-wysihtml5.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{URL('public/assets/css/uploadfile.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{URL('public/assets/css/jquery.tagsinput.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{URL('public/assets/css/table/style.css')}}" rel="stylesheet" type="text/css" />
		<link href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
		<link href="{{URL('public/assets/css/table/jquery.tablesorter.pager.css')}}" rel="stylesheet" type="text/css" />
		<link href="https://cdn.datatables.net/1.10.11/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript">var base_asset = '{{url('')}}/';</script>
		<!--[if lt IE 9]>
		<link rel="stylesheet" href="{{URL::asset('public/assets/css/ie.css')}}" type="text/css" media="screen" />
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

		<!-- Latest compiled and minified CSS & JS -->
		<link rel="stylesheet" media="screen" href="//netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

		<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<!-- Bootstrap -->

		<script type="text/javascript" src="{{URL('public/assets/js/wysihtml5.min.js')}}"></script>
		<script type="text/javascript" src="{{URL('public/assets/js/bootstrap-wysihtml5.min.js')}}"></script>
		<script src="{{URL::asset('public/assets/js/hideshow.js')}}" type="text/javascript"></script>

		<script type="text/javascript" src="{{URL::asset('public/assets/js/jquery.equalHeight.js')}}"></script>
		<script type="text/javascript" src="{{URL::asset('public/assets/js/jquery.uploadfile.js')}}"></script>
		<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />

		<!-- <script src="{{URL::asset('public/assets/js/jquery.uploadify-3.1.min.js')}}"></script> -->
		<script src="{{URL::asset('public/assets/js/jquery.magnific-popup.min.js')}}"></script>

		<script type="text/javascript" src="{{URL::asset('public/assets/js/jquery.metadata.js')}}"></script>
		<script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js" type="text/javascript"></script>
		<script src="{{URL::asset('public/assets/js/jquery.tablesorter.min.js')}}" type="text/javascript"></script>
		<script src="{{URL::asset('public/assets/js/jquery.tablesorter.pager.js')}}" type="text/javascript"></script>
		<script src="{{URL::asset('public/assets/js/jquery.tagsinput.js')}}" type="text/javascript"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
		<style type="text/css">
			table.dataTable thead .sorting_desc{
				background-image:none !important;
			}
			table.dataTable thead .sorting_asc{
				background-image:none !important;
			}
		</style>
		<script type="text/javascript">
			var _token = '{{ csrf_token() }}';
			var base_url = '{{url("")}}';
			var base_asset = '{{url('')}}/';

			jQuery.browser = {};
			(function () {
				jQuery.browser.msie = false;
				jQuery.browser.version = 0;
				if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
					jQuery.browser.msie = true;
					jQuery.browser.version = RegExp.$1;
				}
			})();
			$(document).ready(function(){

				$(".tablesorter").tablesorter().tablesorterPager({container: $("#pager"), size:20});
				$('.table_dashborad').tablesorter();

				$(function(){
					$('.column').equalHeight();
				});

				//When page loads...
				$(".tab_content").hide(); //Hide all content
				$("ul.tabs li:first").addClass("active").show(); //Activate first tab
				$(".tab_content:first").show(); //Show first tab content

				//On Click Event
				$("ul.tabs li").click(function() {

					$("ul.tabs li").removeClass("active"); //Remove any "active" class
					$(this).addClass("active"); //Add "active" class to selected tab
					$(".tab_content").hide(); //Hide all tab content

					var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
					$(activeTab).fadeIn(); //Fade in the active ID content
					return false;
				});

				$('.wysiwyg').wysihtml5({
					"font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
					"emphasis": true, //Italics, bold, etc. Default true
					"lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
					"html": true, //Button which allows you to edit the generated HTML. Default false
					"link": true, //Button to insert a link. Default true
					"image": true, //Button to insert an image. Default true,
					"color": true //Button to change color of font
				});

				$('.email-templete').wysihtml5({
					"font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
					"emphasis": true, //Italics, bold, etc. Default true
					"lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
					"html": true, //Button which allows you to edit the generated HTML. Default false
					 "link": true, //Button to insert a link. Default true
					"image": true, //Button to insert an image. Default true,
					"color": true //Button to change color of font
				});
			});

		</script>
		@yield('script')
	</head>

	<body>

		<header id="header">
			<hgroup>
				<h1 class="site_title"><a href="{{URL('admincp')}}">Administrator</a></h1>
				<h2 class="section_title">@yield('title')</h2><div class="btn_view_site"><a target="_New" class="fa fa-home" href="{{URL()}}"> Home</a></div>
			</hgroup>
		</header> <!-- end of header bar -->

		<section id="secondary_bar">
			<div class="user">
				<p><?php if(\Session::has('logined')){$user=\Session::get('logined'); echo $user->username; }?></p>
			</div>
			<div class="breadcrumbs_container">
				<article class="breadcrumbs"><a href="{{URL('admincp')}}">Dashboard</a><div class="breadcrumb_divider"></div> <a class="current">@yield('subtitle')</a>@if(isset($title_pornstar))<div class="breadcrumb_divider"></div><a class="current">{{$title_pornstar}}</a>@endif @if(isset($porn_name))<div class="breadcrumb_divider"></div><a class="current">{{$porn_name}}</a>@endif<div class="breadcrumb_divider"></div><a class="current">@yield('title')</a></article>
			</div>
		</section><!-- end of secondary bar -->

		<aside id="sidebar" class="col-md-3">
			<hr/>
			<h3 class="fa fa-external-link"> Category Management</h3>
			<ul class="toggle" style="display: block !important">
				<li><a <?php if(Request::segment(2)=="categories") echo "class='current'" ?> href="{{URL('admincp/categories')}}">Video Categories Manager</a></li>
				<li><a <?php if(Request::segment(2)=="add-categories") echo "class='current'" ?> href="{{URL('admincp/add-categories')}}">Add A New Category</a></li>
			</ul>
			<h3 class="fa fa-external-link"> Channel Management</h3>
			<ul class="toggle">
				<li><a <?php if(Request::segment(2)=="channel") echo "class='current'" ?> href="{{URL('admincp/channel')}}">Manage An Existing Channel </a></li>
				<li><a <?php if(Request::segment(2)=="add-channel") echo "class='current'" ?>  href="{{URL('admincp/add-channel')}}">Add A New Channel</a></li>
				<li><a <?php if(Request::segment(2)=="channel-subscriber") echo "class='current'" ?> href="{{URL('admincp/channel-subscriber')}}">Manage Channel Subscriptions</a></li>
			</ul>
			<h3 class="fa fa-external-link"> Pornstar Management</h3>
			<ul class="toggle">
				<li><a <?php if(Request::segment(2)=="pornstar") echo "class='current'" ?> href="{{URL('admincp/pornstar')}}">Manage an Existing Pornstar </a></li>
				<li><a <?php if(Request::segment(2)=="add-pornstar") echo "class='current'" ?> href="{{URL('admincp/add-pornstar')}}">Add A New Pornstar</a></li>
			</ul>
			<h3 class="fa fa-video-camera"> Video Management</h3>
			<ul class="toggle">
				<li><a <?php if(Request::segment(2)=="video") echo "class='current'" ?> href="{{URL('admincp/video')}}">Manage Existing Videos </a></li>
				<li><a <?php if(Request::segment(2)=="add-video") echo "class='current'" ?> href="{{URL('admincp/add-video')}}">Add A New Video</a></li>
				<li><a <?php if(Request::segment(2)=="add-multi-video") echo "class='current'" ?> href="{{URL('admincp/add-multi-video')}}">Bulk Upload Video</a></li>
				<li><a <?php if(Request::segment(2)=="dowload-video-add-view") echo "class='current'" ?> href="{{URL('admincp/dowload-video-add-view')}}">Download Video Content </a></li>
				<li><a <?php if(Request::segment(2)=="duplicates-video") echo "class='current'" ?> href="{{URL('admincp/duplicates-video')}}">Duplicate Existing Videos </a></li>
				<li><a <?php if(Request::segment(2)=="delete-video-csv") echo "class='current'" ?> href="{{URL('admincp/delete-video-csv')}}">Delete Videos CSV </a></li>
				<li><a <?php if(Request::segment(2)=="member-video-upload") echo "class='current'" ?> href="{{URL('admincp/member-video-upload')}}">Member's Video Upload</a></li>
			</ul>

			<h3 class="fa fa-users"> User Management</h3>
			<ul class="toggle">
				<li><a <?php if(Request::segment(2)=="user") echo "class='current'" ?> href="{{URL('admincp/user')}}">Manage Existing Users </a></li>
				<li><a <?php if(Request::segment(2)=="banip") echo "class='current'" ?> href="{{URL('admincp/banip')}}">Banned IP Address Management </a></li>
			</ul>
			<h3 class="fa fa-comments"> Manage Comments</h3>
			<ul class="toggle">
				<li><a <?php if(Request::segment(2)=="video-comment") echo "class='current'" ?> href="{{URL('admincp/video-comment')}}">Manage Video Comments </a></li>
			</ul>

			<h3 class="fa fa-external-link"> Advertisement Management</h3>
			<ul class="toggle">
				<li><a <?php if(Request::segment(2)=="ads-standard") echo "class='current'" ?> href="{{URL('admincp/ads-standard')}}">Manage Standard Ads</a></li>
				<li><a <?php if(Request::segment(2)=="ads-text-video") echo "class='current'" ?> href="{{URL('admincp/ads-text-video')}}">Manage Text Ads</a></li>
				<li><a <?php if(Request::segment(2)=="ads-video") echo "class='current'" ?> href="{{URL('admincp/ads-video')}}">Manage Video Ads</a></li>
			</ul>
			<h3 class="fa fa-shopping-cart"> Payment</h3>
			<ul class="toggle">
				<li><a <?php if(Request::segment(2)=="payment-manage") echo "class='current'" ?> href="{{URL('admincp/payment-manage')}}">Payment Management</a></li>
			</ul>
			<h3 class="fa fa-external-link"> Static Pages </h3>
			<ul class="toggle">
				<li><a  <?php if(Request::segment(2)=="static-page") echo "class='current'" ?> href="{{URL('admincp/static-page')}}">Manage Static Pages</a></li>
			</ul>
			<h3 class="fa fa-external-link"> Tags </h3>
			<ul class="toggle">
				<li><a  <?php if(Request::segment(2)=="tag") echo "class='current'" ?> href="{{URL('admincp/tag')}}">Manage Tags</a></li>
				<li><a  <?php if(Request::segment(2)=="add-tag") echo "class='current'" ?> href="{{URL('admincp/add-tag')}}">Add A Tag</a></li>
			</ul>
			<h3 class="fa fa-cogs"> Language</h3>
			<ul class="toggle">
				<li><a <?php if(Request::is("*/language/setting")) echo "class='current'" ?>media="" href="{{URL('admincp/language/setting')}}">Language settings</a></li>
				<li><a <?php if(Request::is("*/language/all")) echo "class='current'" ?> href="{{URL('admincp/language/all')}}">All Language</a></li>
				<!-- <li><a href="{{URL('admincp/language/stranlate')}}">Stranlate Language</a></li> -->
			</ul>
			<h3 class="fa fa-cogs"> Settings</h3>
			<ul class="toggle">
				<li><a <?php if(Request::segment(2)=="conversion-config") echo "class='current'" ?> href="{{URL('admincp/conversion-config')}}">Conversion settings</a></li>
				<li><a <?php if(Request::segment(2)=="payment-setting") echo "class='current'" ?> href="{{URL('admincp/payment-setting')}}">Payment Gateway Settings</a></li>
				<li><a <?php if(Request::segment(2)=="video-setting") echo "class='current'" ?> href="{{URL('admincp/video-setting')}}">Video Settings</a></li>
				<li><a <?php if(Request::segment(2)=="email-templete") echo "class='current'" ?> href="{{URL('admincp/email-templete')}}">Email Template</a></li>
				<li><a <?php if(Request::segment(2)=="email-setting") echo "class='current'" ?> href="{{URL('admincp/email-setting')}}">Email Settings</a></li>
				<li><a <?php if(Request::segment(2)=="option") echo "class='current'" ?> href="{{URL('admincp/option')}}">META Tag, Analytics and Site Map Information</a></li>
				<li><a <?php if(Request::segment(2)=="theme-setting") echo "class='current'" ?> href="{{URL('admincp/theme-setting')}}">Theme</a></li>
			</ul>
			<h3 class="fa fa-user-secret"> Administrators</h3>
			<ul class="toggle">
				<li><a <?php if(Request::segment(2)=="contact") echo "class='current'" ?> href="{{URL('admincp/contact')}}">Contact</a></li>
				<li><a <?php if(Request::segment(2)=="clear-cache") echo "class='current'" ?> href="{{URL('admincp/clear-cache')}}">Clear Cache</a></li>
				<li><a <?php if(Request::segment(2)=="all-faq") echo "class='current'" ?> href="{{URL('admincp/all-faq')}}">FAQ</a></li>
				<li><a <?php if(Request::segment(2)=="header-link") echo "class='current'" ?> href="{{URL('admincp/header-link')}}">Header link</a></li>
				<li><a <?php if(Request::segment(2)=="change-password") echo "class='current'" ?> href="{{URL('admincp/change-password')}}">Change Password</a></li>
				<li><a <?php if(Request::segment(2)=="change-email") echo "class='current'" ?> href="{{URL('admincp/change-email')}}">Change Email</a></li>
				<li><a <?php if(Request::segment(2)=="logout") echo "class='current'" ?> href="{{URL('admincp/logout')}}">Logout</a></li>
			</ul>
			<footer>
				<hr />
				<p><strong>Copyright &copy; {{date("Y")}} Website Admin</strong></p>
			</footer>
		</aside><!-- end of sidebar -->

		<section id="main" class="col-md-9">

			@yield('content')

		</section>

		<div id="jax-loading" style="" class="modal fade" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<center><img width="60" height="60" src="{{URL('public/assets/images/loading_apple.gif')}}"/></center>
			</div>
		</div>
		@include('cookie.cookie')
	</body>

</html>
