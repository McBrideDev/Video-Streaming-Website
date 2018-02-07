<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EmailSettingModel extends Model  {

	protected $table="email_setting";

	public static function get_temp_confirm_sign_up(){
		$get_temp= EmailSettingModel::select('email_setting.*','email_templete.name_slug')
		->join('email_templete','email_templete.id','=','email_setting.registration_email')
		->first();
		if($get_temp!=null){
			return $get_temp;
		}
		return ;
	}

	public static function get_temp_member_reset_password(){
		$get_temp = EmailSettingModel::select('email_setting.*','email_templete.name_slug')
		->join('email_templete','email_templete.id','=','email_setting.member_forgot_password_email')
		->first();
		
		if($get_temp!=NULL){
			return $get_temp;
		}
		return ;
	}

	public static function get_temp_admin_reset_password(){
		$get_temp = EmailSettingModel::select('email_setting.*','email_templete.name_slug')
		->join('email_templete','email_templete.id','=','email_setting.admin_forgot_password_email')
		->first();
		if($get_temp!=NULL){
			return $get_temp;
		}
		return ;
	}

	public static function get_channel_email_register(){
		$get_temp = EmailSettingModel::select('email_setting.*','email_templete.name_slug')
		->join('email_templete','email_templete.id','=','email_setting.channel_register_email')
		->first();
		if($get_temp!=NULL){
			return $get_temp;
		}
		return ;
	}

	public static function get_channel_subscribe_user_email(){
		$get_temp = EmailSettingModel::select('email_setting.*','email_templete.name_slug')
		->join('email_templete','email_templete.id','=','email_setting.channel_subscribe_user_email')
		->first();
		if($get_temp!=NULL){
			return $get_temp;
		}
		return ;
	}

	public static function get_payment_email(){
		$get_temp = EmailSettingModel::select('email_setting.*','email_templete.name_slug')
		->join('email_templete','email_templete.id','=','email_setting.payment_email')
		->first();
		if($get_temp!=NULL){
			return $get_temp;
		}
		return ;
	}
	
}
