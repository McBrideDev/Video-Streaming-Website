<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Helper\AppHelper;

class LanguageSettingsModel extends Model {

	protected $table = "language_settings";
	protected $fillable = ['isLanguage', 'defaultLanguage'];

	public function save(array $options = array()) {
		$data = parent::save($options);

		AppHelper::clearLanguageConfigCache();
		return $data;
	}

	public function update(array $attributes = array()) {
		$data = parent::update($attributes);

		AppHelper::clearLanguageConfigCache();
		return $data;
	}

	public function delete() {
		$data = parent::delete();

		AppHelper::clearLanguageConfigCache();
		return $data;
	}
}
