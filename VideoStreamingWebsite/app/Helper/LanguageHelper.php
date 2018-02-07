<?php
namespace App\Helper;

use Illuminate\Support\Facades\Cache;
use App\Helper\AppHelper;
use App\Models\LanguageModel;
use App\Models\LanguageSettingsModel;

/**
 * page helper class
 */
class LanguageHelper {
  const CACHE_LANGUAGE_LIST = 'LANGUAGE_LIST';

  /**
   * read static page model from cache or DB
   * @param type $pageId
   */
  public static function getActiveLanguges() {
    //return Cache::remember(self::CACHE_LANGUAGE_LIST, AppHelper::DEFAULT_CACHE_IN_MINUTE, function() {
      $setting = AppHelper::getLanguageConfig();
      if (empty($setting)) {
        return null;
      }

      //get active langauges
      $languages = LanguageModel::where('status', '=', 'active')
                    ->orderby('languageCode', 'desc')->get();
      return count($languages) ? $languages : null;
    //});
  }

  public static function checkActiveMultiLanguage() {
    $languageSetting = LanguageSettingsModel::first();
    if(empty($languageSetting)) {
      return false;
    }
    return $languageSetting->isLanguage == 'inactive' ? false : true;
  }

  /**
   * remove language cache
   */
  public static function clearCache() {
    Cache::forget(self::CACHE_LANGUAGE_LIST);
  }
}