<?php
namespace App\Helper;

use App\Models\VideoTextAdsModel;
use Illuminate\Support\Facades\Cache;
use App\Helper\AppHelper;

/**
 * helper class
 */
class AdsHelper {
  const CACHE_ADS_TEXT_LIST = 'ADS_TEXT_LIST';

  /**
   * get text ads
   * @return type
   */
  public static function getActiveAdsTextList() {
    return Cache::remember(self::CACHE_ADS_TEXT_LIST, AppHelper::DEFAULT_CACHE_IN_MINUTE, function() {
      $Ads = VideoTextAdsModel::where('status', '=', 1)->get();
      $array = array();

      for ($i = 0; $i < count($Ads); $i++) {
        array_push($array, array(
            'point' => $Ads[$i]->cuepoint,
            'content' => $Ads[$i]->ads_content,
            'adsurl' => $Ads[$i]->return_url,
            'timedelay' => $Ads[$i]->delay_time,
            'position' => $Ads[$i]->position,
            'ads_title' => $Ads[$i]->ads_title
        ));
      }

      return $array;
    });
  }

  /**
   * clear cache of ads
   */
  public static function clearCache() {
    Cache::forget(self::CACHE_ADS_TEXT_LIST);
  }
}