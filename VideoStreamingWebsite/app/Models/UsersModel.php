<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UsersModel extends Model  {

	protected $table='npt_users';


	public static function CheckLogin($username,$password){
		$check = UsersModel::where('username','=',$username)->where('password','=',$password)->count();
		if($check>0) {
			//Session(["logined"=>Users::where('username','=',$username)->first()]);
			\Session::put("logined",UsersModel::where('username','=',$username)->first());
			return true;
		} else {
			return false;
		}
	}

	public static function getAdminEmail(){
		$get = UsersModel::where('username','=',"admin")->first();
		if($get) {
			return $get->email;
		} else {
			return "";
		}
	}

	public static function CheckUserName($username){
		$count = UsersModel::where('username','=',$username)->get()->count();
		if($count==1)
			return true;
		else 
			return false;
	}

	public static function AddUser($username,$password,$email,$is_active,$id_group){
		$user = new UsersModel();
		$user-> username = $username;
		$user-> password = $password;
		$user-> email = $email;
		$user-> id_group = $id_group;
		$user-> is_active = $is_active;
		$user->save();
	}

	public static function EditUser($id,$username,$password,$email,$is_active, $id_group){
		$user = UsersModel::find($id);
		$user-> username = $username;
		if($user-> password != $password) {
			$user-> password = $password;
		}

		$user-> email = $email;
		$user-> id_group = $id_group;
		$user-> is_active = $is_active;
		$user-> save();
	}

}
