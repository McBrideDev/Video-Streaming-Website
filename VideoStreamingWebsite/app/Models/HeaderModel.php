<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helper\AppHelper;

class HeaderModel extends Model {

	protected $table = "header_link";
	protected $fillable = ['title_name', 'content', 'link', 'status'];

	public static function get_list_link() {
		$list = HeaderModel::where('status', '=', 1)->get();
		$html = "";
		$html.='<div class="ticker-container hidden-xs hidden-sm"><ul>';
		foreach ($list as $result) {
			$html.='<div><li><span>' . $result->title_name . ':</span> ' . $result->content . ' Scene&ndash; <a href="' . $result->link . '">Click here</a></li></div>';
		}
		$html.='</ul></div>';

		return $html;
	}

	public function save(array $options = array()) {
		$data = parent::save($options);

		AppHelper::clearHeaderCache();
		return $data;
	}

	public function delete() {
		$data = parent::delete();

		AppHelper::clearHeaderCache();
		return $data;
	}

	public function update(array $attributes = array()) {
		$data = parent::update($attributes);
		
		AppHelper::clearHeaderCache();
		return $data;
	}
}
