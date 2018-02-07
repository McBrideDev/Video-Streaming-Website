<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helper\AppHelper;

class VideoSettingModel extends Model {

	protected $table = "video_setting";

	//protected $fillable = ['video_id', 'time'];
	public static function get_config() {
		$config = VideoSettingModel::first();
		return $config;
	}

	public function save(array $options = array()) {
		$data = parent::save($options);

		AppHelper::clearVideoConfigCache();
		return $data;
	}

	public function update(array $attributes = array()) {
		$data = parent::update($attributes);

		AppHelper::clearVideoConfigCache();
		return $data;
	}

	public function delete() {
		$data = parent::delete();

		AppHelper::clearVideoConfigCache();
		return $data;
	}
}
