<?php
namespace App\Helper;

use Illuminate\Support\Facades\Cache;
use App\Helper\AppHelper;
use App\Models\StaticPageModel;

/**
 * page helper class
 */
class PageHelper {
	const CACHE_PAGE_PREFIX = 'STATIC_PAGE_';

	/**
	 * read static page model from cache or DB
	 * @param type $pageId
	 */
	public static function getStaticPageById($pageId) {
		$cacheKey = self::CACHE_PAGE_PREFIX . $pageId;

		return Cache::remember($cacheKey, AppHelper::DEFAULT_CACHE_IN_MINUTE, function() use ($pageId) {
			return StaticPageModel::where('id', $pageId)->where('status', 1)->first();
		});
	}

	/**
	 * get url for the page
	 *
	 * @param String $pageId
	 * @return string
	 */
	public static function getStaticPageLink($pageId) {
		$page = self::getStaticPageById($pageId);
		if (!$page) {
			return '';
		}

		if ($page->status == 1) {
			return '<a href="' . URL(getLang(). 'information/'. $pageId)  . '">' . $page->titlename . '</a>';
		}

		return '';
	}

	public static function getAnotherPage() {
		$pages = StaticPageModel::where('status', '=', "1")->whereNotIn('id', ["1", "2", "3", "4", "5", "6", "7", "8", "9"])->get();
		return $pages;
	}

	/**
	 * get url for the page
	 *
	 * @param String $pageId
	 * @return string
	 */
	public static function loadDynamicStaticPages() {
		$pages = StaticPageModel::where('status', '=', "1")->whereNotIn('id', ["1", "2", "3", "4", "5", "6", "7", "8", "9"])->get();
		if ( $pages ) {
			$html = "";
			foreach ($pages as $key => $page) {
				$link = self::getStaticPageLink($page->id);

				$html .= '<li>' . $link . '</li>';
			}

			return $html;
		}

		return '';
	}

	/**
	 * remove single page cache
	 * @param String $pageId
	 */
	public static function clearStaticPageCache($pageId) {
		$cacheKey = self::CACHE_PAGE_PREFIX . $pageId;
		Cache::forget($cacheKey);
	}
}
