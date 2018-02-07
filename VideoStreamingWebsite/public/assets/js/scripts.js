$(document).ready(function($){


		/**************************
		***** FANCY News Sliders **
		**************************/

		$('#basic').fancyNews();

		var $rssFeeds = $('#rss-feeds').fancyNews({
			width: '100%',
			height:510,
			previewsPerPage: 5,
			feed:'http://feeds.feedburner.com/nettuts?format=xml',
		});

		$('#feeds-with-linking').fancyNews({
			width: 500,
			height: 300,
			feed:'http://feeds.feedburner.com/nettuts?format=xml',
			infiniteLoop: true,
			slideTime: 3000,
			useLinks: true,
			arrows: false,
			backgroundColor: '#34495F',
			backgroundOverColor : '#18222D',
			textColor: '#fff',
			primaryColor: '#ECF0F1',
			center: true,
			offset: 10
		});

		$('#vertical-with-linking').fancyNews({
			infiniteLoop: true,
			previewsPerPage: 3,
			vertical: true,
			offset: 0,
			useLinks: true,
			width: 600,
			height: 280,
			center: true
		});

		$('#load-feed').click(function(){
			$rssFeeds.next('.fn-navigation:first').remove();
			$rssFeeds.empty();
			$rssFeeds.fancyNews({
				width: '100%',
				height:510,
				previewsPerPage:5,
				feed:$('#feed-url').val(),
			});
		});

});