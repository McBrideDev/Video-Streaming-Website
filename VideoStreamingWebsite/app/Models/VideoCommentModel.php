<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VideoCommentModel extends Model {

	protected $table = "video_comment";
	protected $fillable = ['video_Id', 'member_Id', 'post_comment', 'status'];
	public function replies() {
		return $this->hasMany('App\Models\VideoCommentModel', 'comment_parent', 'id');
	}

	public function author() {
		return $this->belongsTo('App\Models\MemberModel', 'member_Id', 'user_id');
	}

}
