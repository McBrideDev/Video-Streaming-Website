<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MemberReportModel extends Model  {

	protected $table="member_report";
	protected $fillable = ['user_id', 'member_Id', 'content','status','member_status'];
	
}
