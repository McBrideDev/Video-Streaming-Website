<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helper\AppHelper;

class TagModel extends Model {

	protected $table = "tag";

    public static function clearCacheTag()
    {
        AppHelper::clearSiteTagsCache();
    }

	public static function get_tag() {
		$tag = TagModel::where('status', 1)->get();
		return $tag;
	}

    public function save(array $options = array()) {
        $data = parent::save($options);
        $this->clearCacheTag();
        return $data;
    }

    public function delete() {
        $data = parent::delete();
        $this->clearCacheTag();
        return $data;
    }

    public function update(array $attributes = array()) {
        $data = parent::update($attributes);
        $this->clearCacheTag();
        return $data;
    }
}
