<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Helper;

use App\Models\PornStarModel;
use App\Helper\AppHelper;
use Illuminate\Support\Facades\Cache;

/**
 * Description of PornStarHelper
 *
 * @author knhuynh
 */
class PornStarHelper {

	const CACHE_NUMBER_PORNSTARS = 'COUNT_NUMBER_PORNSTARS';
	const CACHE_LIST_PORNSTARS = 'LIST_PORNSTARS';

	const DEFAULT_CACHE_IN_MINUTE = 20000;

	public static function numberPornStars() {
		return Cache::remember(PornStarHelper::CACHE_NUMBER_PORNSTARS, PornStarHelper::DEFAULT_CACHE_IN_MINUTE, function() {
			return PornStarModel::count();
		});
	}

	public static function getImageUrl($poster) {
		$file_headers = @get_headers($poster);
		// dd($file_headers);
		if ($file_headers[0] == "HTTP/1.0 404 Not Found" || $file_headers[0] == "HTTP/1.1 404 Not Found") {
			return asset('public/assets/images/no-image.jpg');
		}
		return $poster;
	}

}
