<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChanneSubscriberModel extends Model  {
	protected $table="channel_subscriber";
	protected $fillable = ['channel_Id', 'member_Id', 'status'];

	//  private function clearCache() {
	//    Cache::forget(self::CACHE_LIST_CHANNEL_SUBSCRIBED);
	//  }

	public function save(array $options = array()) {
		$data = parent::save($options);

		// $this->clearCache();
		return $data;
	}
}
