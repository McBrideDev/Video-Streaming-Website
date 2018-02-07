<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VideoAdsModel extends Model {

	protected $table = "video_ads";
	protected $fillable = ['string_id', 'adv_url', 'title', 'descr', 'duration', 'media', 'views', 'clicks', 'addtime', 'status'];

	public static function get_ads_video() {
		return VideoAdsModel::where('status', '=', '1')->orderByRaw("RAND()")->first();
	}
}
