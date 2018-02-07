<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PornStarSubscriberModel extends Model  {

	protected $table="pornstar_subscriber";

	protected $fillable = ['channel_Id', 'member_Id', 'status'];
	
}
