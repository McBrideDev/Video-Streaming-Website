<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProfileCommentModel extends Model  {

	protected $table="profile_comment";
	protected $fillable = ['profile_Id', 'member_post_Id', 'post_comment', 'status'];
}
