<?php

namespace App\Helper;
use Illuminate\Support\Facades\Cache;

/**
 * helper class for all events through the app
 */
class NetworkHelper {

  /**
   * get current IP of request
   * @return type
   */
  public static function getRealIpAddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = request()->ip();
    }

    return $ip;
  }

  /**
   *
   * @param type $url
   */
  public static function getVideoTypeFromUrl($url) {
    $cacheKey = 'URL_TYPE_' . md5($url);

    Cache::remember($cacheKey, 30, function() use($url) {
      $ch = curl_init();
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt ($ch, CURLOPT_URL, $url);
      curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
      curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36");
      curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt ($ch, CURLOPT_HEADER, true);
      curl_setopt ($ch, CURLOPT_NOBODY, true);

      curl_exec ($ch);
      $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
      curl_close ($ch);

      return $contentType == 'video/mp4' ? 'video/mp4' : 'video/x-flv';
    });
  }
}
