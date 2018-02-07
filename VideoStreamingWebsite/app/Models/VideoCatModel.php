<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoCatModel extends Model {

	protected $table = "video_cat";
	protected $fillable = ['video_id', 'cat_id'];

	public static $tableName = 'video_cat';
}
