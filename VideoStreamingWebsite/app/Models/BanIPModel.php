<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BanIPModel extends Model {
	const STATUS_BLOCK = 1;
	const STATUS_OPEN = 0;
	const BLACK_LIST_CACHE_KEY = 'BLACK_IP_LIST';

	protected $table = "ipban";

	public static function get_list_ban() {
		$getlist = BanIPModel::get();
		$ip_list = array();
		if (count($getlist) > 0) {

			for ($i = 0; $i < count($getlist); $i++) {
				array_push($ip_list, $getlist[$i]->ip_ban);
			}
			//check banip

			if (in_array(BanIPModel::getRealIpAddr(), $ip_list)) {
				// $url_return ="<script> location.href = 'http://google.com';</script>";
				// return $url_return;
				return redirect('503');
			} else {
				return "";
			}
		} else {
			echo "";
		}
	}

	public static function getRealIpAddr() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	/**
	 * cache black list
	 * @return Array
	 */
	private static function cacheBlackIps() {
		//renew IP back cached list
		$ipArr = self::get()->toArray();
		$ips = [];
		foreach($ipArr as $ip) {
			if ($ip['status'] == 1) {
				$ips[] = $ip['ip_ban'];
			}
		}

		Cache::forever(self::BLACK_LIST_CACHE_KEY, $ips);

		return $ips;
	}

	/**
	 * get block ip in the list or empty array as default
	 * @return type
	 */
	public static function getBlackIps() {
		$cacheList = Cache::get(self::BLACK_LIST_CACHE_KEY);
		if (!$cacheList) {
			return self::cacheBlackIps();
		}

		return $cacheList;
	}

	/**
	 * override save class of ban IP model
	 *
	 * @param array $options
	 */
	public function save(array $options = array()) {
		$resp = parent::save($options);

		self::cacheBlackIps();
		return $resp;
	}

	public function delete() {
		$resp = parent::delete();

		self::cacheBlackIps();
		return $resp;
	}
}
