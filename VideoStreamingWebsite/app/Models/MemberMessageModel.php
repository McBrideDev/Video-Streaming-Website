<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MemberMessageModel extends Model  {

	protected $table="member_message";
	protected $fillable = ['frommember', 'tomember', 'message','message_status'];
}
