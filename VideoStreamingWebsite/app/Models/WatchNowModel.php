<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VideoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WatchNowModel extends Model {

	protected $table = "watch_now";

	public static function check_watch() {
		$check = WatchNowModel::get();
		foreach ($check as $result) {
			$time = (time() - (int) $result->time) / 15;
			if ($time > 15) {
				$getVideo = VideoModel::where('string_Id', '=', $result->video_id)->first();
				if (!empty($getVideo)) {
					WatchNowModel::update_views($result->video_id, $getVideo->total_view);
				}
				$remove_watch = WatchNowModel::where('id', '=', $result->id)->delete();
			}
			return;
		}
	}

	public static function update_views($stringId, $currentView) {
		$get_video = VideoModel::select('video.id')->where('string_Id', '=', $stringId)->first();
		$updateViews = VideoModel::find($get_video->id);
		$updateViews->total_view = (int) ($currentView) + 1;
		$updateViews->save();
	}

}
