<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ConversionModel extends Model  {

	protected $table="conversion_config";
	public static function get_config(){
		$conversion_config=ConversionModel::first();
		return $conversion_config;
	}
	protected $fillable = ['php_cli_path', 'mplayer_path', 'mencoder_path', 'ffmpeg_path', 'flvtool2_path', 'mp4box_path', 'mediainfo_path', 'yamdi_path', 'thumbnail_tool','meta_injection_tool', 'max_thumbnail_w', 'max_thumbnail_h','allowed_extension'  ];
}
