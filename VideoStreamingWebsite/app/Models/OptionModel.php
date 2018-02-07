<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helper\AppHelper;

class OptionModel extends Model {

	protected $table = "config";

	public static function get_config() {
		$config = OptionModel::first();
		return $config;
	}

	public function save(array $options = array()) {
		$data = parent::save($options);

		AppHelper::clearSiteConfigCache();
		return $data;
	}

	/**
	 * Update model
	 * Can't use, can't implement for model
	 * @param  array  $attributes [description]
	 * @return [type]             [description]
	 */
	public function update(array $attributes = array()) {
		$data = parent::update($attributes);

		AppHelper::clearSiteConfigCache();
		return $data;
	}

	public function delete() {
		$data = parent::delete();

		AppHelper::clearSiteConfigCache();
		return $data;
	}
}
