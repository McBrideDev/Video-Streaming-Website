<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helper\VideoHelper;

class SubsriptionModel extends Model {

	protected $table = "subscription";
	protected $fillable = ['user_id', 'channel_id', 'video_id', 'email', 'ipAddress', 'paymentType', 'priceDescription', 'subscriptionInitialPrice', 'referringUrl', 'state', 'subscriptionCurrency', 'subscriptionCurrencyCode', 'subscriptionId', 'subscriptionTypeId', 'initialPeriod', 'timestamp', 'transactionId', '_token', 'status'];

	public static function check_user_buy_video($user_id, $string_Id) {
		return SubsriptionModel::where('user_id', '=', $user_id)
						->where('video_id', '=', $string_Id)
						->count() > 0;
	}

	public function save(array $options = array()) {
		$data = parent::save($options);

		VideoHelper::clearPurchasedCache($this->user_id);
		return $data;
	}

	public function update(array $attributes = array()) {
		$data = parent::update($attributes);

		VideoHelper::clearPurchasedCache($this->user_id);
		return $data;
	}

	public function delete() {
		$data = parent::delete();

		VideoHelper::clearPurchasedCache($this->user_id);
		return $data;
	}
}
