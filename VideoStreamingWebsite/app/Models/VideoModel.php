<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helper\AppHelper;

class VideoModel extends Model {

	protected $table = "video";

	const INACTIVE         = 0;
	const STATUS_COMPLETED = 1;
	const STATUS_FAILED    = 2;
	const CONVERT_STATUS   = 3;
	const IN_PROCESS       = 4;
	const BLOCKED          = 5;
	const FEATURED         = 1;

	// public $timestamps = false;
	protected $fillable = ['user_id', 'string_Id', 'buy_this', 'is_subscription', 'categories_Id', 'cat_id', 'channel_Id', 'pornstar_Id', 'title_name', 'post_name', 'video_src', 'video_sd', 'isEmbed', 'embedCode', 'video_url', 'website', 'dowloader', 'video_type', 'extension', 'poster', 'preview', 'digitsPreffix', 'digitsSuffix', 'duration', 'description', 'status', 'featured', 'total_view', 'comment_status', 'rating', 'tag', 'porn_star', 'form_name', 'allowedTypes', 'subscriptionTypeId', 'convertCount'];
	public static $tubeCopWeb = [
		'hclips.com',
		'hdzog.com',
		'hotmovs.com',
		'thegay.com',
		'tubepornclassic.com',
		'txxx.com',
		'upornia.com',
		'vjav.com',
		'voyeurhit.com'
	];

	public function nestedComments() {
		return $this->hasMany('VideoComment')->where('comment_parent', 0);
	}

	public static function getImageUrl($poster) {
		// if (!empty($poster)) {
		// 	$image = $poster;
		// }else{
		// 	$image = asset('public/assets/images/no-image.jpg');
		// }
		// return $image;
		// return $poster;
		$file_headers = @get_headers($poster);
		// dd($file_headers);
		if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			return asset('public/assets/images/no-image.jpg');
		}
		return $poster;
	}

	// public function save(array $options = array()) {
	//   parent::save($options);

	//   AppHelper::clearVideoCache();
	// }
}
