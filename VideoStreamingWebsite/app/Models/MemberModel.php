<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use File;

class MemberModel extends Model  {

	const ROLE_SUPERADMIN    = 4;
	const ROLE_ADMIN_SITE    = 3;
	const ROLE_EMPLOYEE_SITE = 2;
	const ROLE_ADMIN         = 1;
	const ROLE_EMPLOYEE      = 0;

	protected $table = "members";
	protected $fillable = ['user_id', 'username', 'password','email','avatar','email','firstname','lastname','bio','is_profile_updated','is_public','is_comment','is_addfriend','is_message','is_channel_member'  ];

	public static function checkUserName($username, $id = null) {
		if(!$id){
			$count = self::where('username', '=', $username)->get()->count();
		}else{
			$count = self::where('username', '=', $username)->where('id', '<>', $id)->get()->count();
		}
		return $count == 1;
	}

	public static function getUserOnline($user){
		if($user->roles==1){
			return self::select('firstname')
				->where('company_id','=',$user->id)
				->where('signin','=',1)
				->orwhere('id',$user->id)
				->get();
		}else{
			return self::select('firstname')
				->where('company_id','=',$user->company_id)
				->where('signin','=',1)
				->orwhere('id',$user->company_id)
				->get();
		}
		
	}

	public static function setUserOnline($email){
		return self::where('email','=',$email)->update(['signin'=>1]);
	}

	public static function setUserOffOnline($email){
		$offline= self::where('email','=',$email)->update(['signin'=>0]);
		if($offline){
			\Session::forget("User");
			return true;
		}
		return false;
	}

	public static function CheckImageURL($poster){
		if (!empty($poster) && File::exists(public_path() .'/upload/member/' .$poster)) {
			$image =  asset('public/upload/member')."/".$poster;
		}else{
			$image = asset('public/upload/member/no_member.png');
		}
		return $image;
	}
}
