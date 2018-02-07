<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use File;
use Cache;

class ChannelModel extends Model {

	protected $table = "channel";
	protected $fillable = ['title_name', 'post_name', 'poster', 'description', 'status', 'subscribe_status', 'total_view'];

	const ACTIVE = 1;
	const INACTIVE = 0;
	const APPROVED = 2;
	const SUBSCRIBE_ACTIVE = 1;
	const SUBSCRIBE_INACTIVE = 0;
	const IS_CHANNEL_MEMBER = 1;

	const CACHE_DETAIL_PREFIX = 'CHANNEL_MODEL_';

	public static function getImageUrl($poster) {
		if (File::exists(public_path('upload/channel/' . $poster))) {
			$image = asset('public/upload/channel') . "/" . $poster;
		} else {
			$image = asset('public/assets/images/no-image.jpg');
		}
		return $image;
	}

	private function clearCache() {
		Cache::forget(self::CACHE_DETAIL_PREFIX . $this->id);
	}
	public function save(array $options = array()) {
		$data = parent::save($options);

		$this->clearCache();
		return $data;
	}

	public function delete() {
		$data = parent::delete();

		$this->clearCache();
		return $data;
	}

	public function update(array $attributes = array()) {
		$data = parent::update($attributes);

		$this->clearCache();
		return $data;
	}

	public static function removeById($id)
	{
		$channel = ChannelModel::find($id);
        if(!empty($channel)){
            if(!empty($channel->poster)){
                if(File::exists(public_path()."/upload/channel/".$channel->poster)){
                    unlink(public_path()."/upload/channel/".$channel->poster);
                }
            }
            return $channel->delete();
        }
        return false;
	}

}
