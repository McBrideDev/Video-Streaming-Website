<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helper\LanguageHelper;

class LanguageModel extends Model {

	protected $table = "language";
	protected $filable = ['languageName', 'languageCode'];

	public function save(array $options = array()) {
		$data = parent::save($options);
		LanguageHelper::clearCache();

		return $data;
	}

	public function delete() {
		$data = parent::delete();
		LanguageHelper::clearCache();

		return $data;
	}

	public function update(array $attributes = array()) {
		$data = parent::update($attributes);

		LanguageHelper::clearCache();
		return $data;
	}
}
