<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MemberVideoModel extends Model  {

	protected $table="member_video";
	protected $fillable = ['member_Id', 'video_Id', 'title_name','post_name','status'];

}
