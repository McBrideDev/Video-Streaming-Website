<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helper\PageHelper;

class StaticPageModel extends Model {

	protected $table = "static_page";
	protected $fillable = ['titlename', 'content_page', 'status'];

	public static function show_link($id) {
		$checkid = StaticPageModel::find($id);
		if ($checkid != NULL) {
			if ($checkid->status == 1) {
				$html = '<a href="' . URL(getLang() . "infomations/") . "/" . $id . '">' . $checkid->titlename . '</a>';
				return $html;
			}
		} else {
			return redirect('')->with('msg', ' Page not found !');
		}
	}

	public function save(array $options = array()) {
		$data = parent::save($options);

		PageHelper::clearStaticPageCache($this->id);
		return $data;
	}

	public function delete() {
		$data = parent::delete();
		
		PageHelper::clearStaticPageCache($this->id);
		return $data;
	}
}
