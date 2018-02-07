<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentConfigModel extends Model {

	protected $table = "paymentconfig";

	public function update_config($id, $data) {
		PaymentConfigModel::find($id)->update($data);
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
