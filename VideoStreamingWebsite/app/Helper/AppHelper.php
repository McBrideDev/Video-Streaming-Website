<?php
namespace App\Helper;

use App\Models\BanIPModel;
use App\Models\VideoSettingModel;
use App\Models\OptionModel;
use App\Models\TagModel;
use App\Models\VideoAdsModel;
use App\Models\LanguageSettingsModel;
use App\Models\PaymentConfigModel;
use App\Models\CategoriesModel;
use App\Models\LanguageModel;
use App\Models\HeaderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/**
 * helper class for all events through the app
 */
class AppHelper {

	private static $currentLanguage;
	
	private $siteConfig;
	private $siteTag;
	private $videoConfig;
	private $languageConfig;
	private $paymentConfig;
	private $categoryList;

	const CACHE_LIST_CATEGORIES = 'LIST_CATEGORIES';
	const CACHE_FEATURED_VIDEO_HOME_LIST = 'FEATURED_VIDEO_HOME_LIST';
	const CACHE_TODAY_VIDEO_HOME_LIST = 'TODAY_VIDEO_HOME_LIST';
	const CACHE_TODAY_RARING_VIDEO_HOME_LIST = 'TODAY_RARING_VIDEO_HOME_LIST';
	const CACHE_TODAY_POST_VIDEO_HOME_LIST = 'TODAY_POST_VIDEO_HOME_LIST';
	const CACHE_HEADER_LINKS = 'HEADER_LINKS';
	const CACHE_SITE_CONFIG = 'CACHE_SITE_CONFIG';
	const CACHE_SITE_TAG = 'CACHE_SITE_TAG';
	const CACHE_VIDEO_CONFIG = 'CACHE_VIDEO_CONFIG';
	const CACHE_LANGUAGE_CONFIG = 'CACHE_LANGUAGE_CONFIG';
	const CACHE_PAYMENT_CONFIG = 'CACHE_PAYMENT_CONFIG';
	const CACHE_LIST_CHANNEL_SUBSCRIBED = 'LIST_CHANNEL_SUBSCRIBED';

	const DEFAULT_CACHE_IN_MINUTE = 20000;

	/**
	 * get site config
	 * @return \App\Model\OptionModel
	 */
	public static function getSiteConfig() {
		$siteConfig = Cache::get(self::CACHE_SITE_CONFIG);
		if (NULL === $siteConfig) {
			$siteConfig = Cache::remember(self::CACHE_SITE_CONFIG, self::DEFAULT_CACHE_IN_MINUTE, function() {
				return OptionModel::get_config();
			});
		}

		return $siteConfig;
	}

	/**
	 * clear cache for site config
	 */
	public static function clearSiteConfigCache() {
		Cache::forget(self::CACHE_SITE_CONFIG);
	}

	/**
	 * get site tags
	 * @return \App\Model\OptionModel
	 */
	public static function getSiteTags() {
		$siteTag = Cache::get(self::CACHE_SITE_TAG);
		if (NULL === $siteTag) {
			$siteTag = Cache::remember(self::CACHE_SITE_TAG, self::DEFAULT_CACHE_IN_MINUTE, function() {
				return TagModel::get_tag();
			});
		}

		return $siteTag;
	}

	/**
	 * clear cache for site tags
	 */
	public static function clearSiteTagsCache() {
		Cache::forget(self::CACHE_SITE_TAG);
	}

	/**
	 * get singleton of video config
	 * @return \App\Model\VideoSettingModel
	 */
	public static function getVideoConfig() {
		$videoConfig = Cache::get(self::CACHE_VIDEO_CONFIG);
		if (NULL === $videoConfig) {
			$videoConfig = Cache::remember(self::CACHE_VIDEO_CONFIG, self::DEFAULT_CACHE_IN_MINUTE, function() {
				return VideoSettingModel::get_config();
			});
		}

		return $videoConfig;
	}

	/**
	 * clear cache for video config
	 */
	public static function clearVideoConfigCache() {
		Cache::forget(self::CACHE_VIDEO_CONFIG);
	}

	/**
	 * get singleton of language config
	 * @return \App\Model\VideoSettingModel
	 */
	public static function getLanguageConfig() {
		$languageConfig = Cache::get(self::CACHE_LANGUAGE_CONFIG);
		if (NULL === $languageConfig) {
			$languageConfig = Cache::remember(self::CACHE_LANGUAGE_CONFIG, self::DEFAULT_CACHE_IN_MINUTE, function() {
				return LanguageSettingsModel::first();
			});
		}
		return $languageConfig;
	}

	public static function clearLanguageConfigCache() {
		Cache::forget(self::CACHE_LANGUAGE_CONFIG);
	}

	/**
	 * get singleton of payment config
	 * @return \App\Model\VideoSettingModel
	 */
	public static function getPaymentConfig() {
		$paymentConfig = Cache::get(self::CACHE_PAYMENT_CONFIG);
		if (NULL === $paymentConfig) {
			$paymentConfig = Cache::remember(self::CACHE_PAYMENT_CONFIG, self::DEFAULT_CACHE_IN_MINUTE, function() {
				return PaymentConfigModel::first();
			});
		}

		return $paymentConfig;
	}

	public static function clearPaymentConfigCache() {
		Cache::forget(self::CACHE_PAYMENT_CONFIG);
	}

	/**
	 * get random video ads
	 * @return \App\Model\VideoAdsModel
	 */
	public static function getPlayerAds() {
		return VideoAdsModel::get_ads_video();
	}

	/**
	 * rediect user to 503 page if IP was blocked!
	 */
	public static function preventBlockedIp() {
		$ip = NetworkHelper::getRealIpAddr();
		//check IP in the blacklist
		if (in_array($ip, BanIPModel::getBlackIps())) {
			echo 'Forbidden!';
			exit(403);
		}
	}

	/**
	 * get list categories sort by name
	 *
	 * @return Array
	 */
	public static function getCategoryList() {
		$categoryList = Cache::get(self::CACHE_LIST_CATEGORIES);
		if (NULL === $categoryList) {
			$categoryList = Cache::remember(AppHelper::CACHE_LIST_CATEGORIES, AppHelper::DEFAULT_CACHE_IN_MINUTE, function() {
				return CategoriesModel::where('status', '=', '1')
							->orderby('title_name', 'asc')
							->get();
			});
		}

		return $categoryList;
	}

	/**
	 * clear cache key for category
	 */
	public static function clearCategoryCache() {
		//TODO - add more key
		Cache::forget(self::CACHE_LIST_CATEGORIES);
	}

	/**
	 * clear cache for video
	 */
	public static function clearVideoCache() {
		Cache::forget(self::CACHE_FEATURED_VIDEO_HOME_LIST);
		Cache::forget(self::CACHE_TODAY_VIDEO_HOME_LIST);
		Cache::forget(self::CACHE_TODAY_RARING_VIDEO_HOME_LIST);
		Cache::forget(self::CACHE_TODAY_POST_VIDEO_HOME_LIST);
	}

	/**
	 * get default language config
	 *
	 * @return type
	 */
	public static function getLang() {
		if (NULL === static::$currentLanguage) {
			// //DONT know why we have this function??
			// $config = self::getLanguageConfig();

			// if (empty($config)) {
			// 	static::$currentLanguage = '';
			// }

			// if ($config->isLanguage === 'active') {
			// 	$req = new Request();
			// 	$checkUri = LanguageModel::select('languageCode')
			// 						->whereIn('languageCode', [$req->segment(1)])->first();
			// 	if (!empty($checkUri)) {
			// 		static::$currentLanguage = '/' . $checkUri->languageCode . '/';
			// 	}
			// 	//load Default
			// 	$loadLanguage = LanguageModel::find($config->defaultLanguage);

			// 	if (!empty($loadLanguage)) {
			// 		static::$currentLanguage = '/' . $loadLanguage->languageCode . '/';
			// 	} else {
			// 		static::$currentLanguage = '';
			// 	}
			// } else {
			// 	static::$currentLanguage = '';
			// }

			static::$currentLanguage = LaravelLocalization::getCurrentLocale();
		}

		return static::$currentLanguage;
	}

	public static function getHeaderLinks() {
		return Cache::remember(self::CACHE_HEADER_LINKS, self::DEFAULT_CACHE_IN_MINUTE, function() {
			$list = HeaderModel::where('status', '=', 1)->get();
			$html = "";
			$html.='<div class="ticker-container hidden-xs hidden-sm"><ul>';
			foreach ($list as $result) {
				$html.='<div><li><span>' . $result->title_name . ':</span> ' . $result->content . ' Scene&ndash; <a href="' . $result->link . '">Click here</a></li></div>';
			}
			$html.='</ul></div>';

			return $html;
		});
	}

	public static function clearHeaderCache() {
		Cache::forget(self::CACHE_HEADER_LINKS);
	}

}
