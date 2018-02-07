<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VideoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PornStarPhotoModel extends Model  {

	protected $table="pornstar_photo";
	protected $fillable = ['pornstar_id', 'photo', 'status'];
	public function getVideoByPornStarId($id)
	{
		$video = VideoModel::where('pornstar_Id','=', $id)->count();
		if (!empty($video)) {
			return $video;
		}else{
			return 0;
		}
	}
}
