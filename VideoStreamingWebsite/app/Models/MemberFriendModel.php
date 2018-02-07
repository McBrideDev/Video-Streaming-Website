<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MemberFriendModel extends Model  {

	protected $table="member_friend";
	protected $fillable = ['member_Id', 'member_friend', 'status'];
	

}
