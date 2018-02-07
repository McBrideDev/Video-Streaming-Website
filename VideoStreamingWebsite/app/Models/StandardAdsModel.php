<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StandardAdsModel extends Model  {

	protected $table="standard_ads";
	protected $fillable = ['string_id', 'position', 'delay_time', 'ads_content', 'script_code', 'ads_type', 'type', 'cl_version', 'ads_title', 'return_url', 'status'];
	public static function get_standard_home(){
		$get_ads= StandardAdsModel::where('position','=','home')->take(1)->orderByRaw("RAND()")->first();
		if($get_ads!=NULL){
			if($get_ads->type=="upload"){
				$html="<a target='_new' href='".$get_ads->return_url."' title='".$get_ads->ads_title."'><img src='".$get_ads->ads_content."' style='max-height:269px'/></a>";
				return $html;
			}
			if($get_ads->type=="script_code"){
				$html='<div id="AD_ID" align="center">'.$get_ads->script_code.'</div>';
				return $html;
			}
		}else{
			$html="<a target='_new' href='#' title=''><img src='".asset('public/assets/images/ads-here.jpg')."' /></a>";
			return $html;
		}
	}

	public static function get_standard_footer(){
		$get_ads= StandardAdsModel::where('position','=','footer')->where('status','=', '1')->take(4)->orderByRaw("RAND()")->get();
		if(count($get_ads)>0){
				foreach ($get_ads as $result) {
					if($result->type=="upload"){
						$html="<div align='center' class='col-md-3 footer-ads-content'><a target='_new' href='".$result->return_url."' title='".$result->ads_title."'><img src='".$result->ads_content."' style='max-height:280px ;height: 280px;max-width: 308px !important;width: 300px; margin-bottom:15px;'/></a></div>";
						echo $html;
					}
					if($result->ads_type=="swf"){
						$html='<div align="center" class="col-md-3 footer-ads-content"><embed style="max-height:132px" name="plugin" src="'.public_path().'/upload/ads/2OqU7KITyR.swf" type="application/x-shockwave-flash"></div>';
						echo $html;
					}
					if($result->type=="script_code"){
						$html='<div  align="center" class="col-md-3 footer-ads-content"><div id="AD_ID" style="max-height:350px">'.$result->script_code.'</div></div>';
						echo $html;
					}
				}


		}else{
			$html="<a target='_new' style='width: 100%;float: left;text-align: center;' href='#' title=''><img src='".asset('public/assets/images/ads-here.jpg')."' /></a>";
			return $html;
		}
	}

	public static function get_standard_toprate(){
		$get_ads= StandardAdsModel::where('position','=','toprate')->take(1)->orderByRaw("RAND()")->first();
		if($get_ads!=NULL){
				if($get_ads->type=="upload"){
					$html="<a target='_new' href='".$get_ads->return_url."' title='".$get_ads->ads_title."'><img src='".$get_ads->ads_content."' style='max-height:269px'/></a>";
					return $html;
				}
				if($get_ads->type=="script_code"){
					$html='<div id="AD_ID">'.$get_ads->script_code.'</div>';
					return $html;
				}
				if($get_ads->ads_type=="swf"){
					$html='<embed style="max-height:269px" name="plugin" src="'.public_path().'/upload/ads/2OqU7KITyR.swf" type="application/x-shockwave-flash">';
					return $html;
				}
		}else{
			$html="<a target='_new' href='#' title=''><img src='".asset('public/assets/images/ads-here.jpg')."' /></a>";
			return $html;
		}

	}

	public static function get_standard_mostview(){
		$get_ads= StandardAdsModel::where('position','=','mostview')->take(1)->orderByRaw("RAND()")->first();
		if($get_ads!=NULL){
				if($get_ads->type=="upload"){
					$html="<a target='_new' href='".$get_ads->return_url."' title='".$get_ads->ads_title."'><img src='".$get_ads->ads_content."' style='max-height:269px'/></a>";
					return $html;
				}
				if($get_ads->type=="script_code"){
					$html='<div id="AD_ID">'.$get_ads->script_code.'</div>';
					return $html;
				}
				if($get_ads->ads_type=="png"){
					$html="<a target='_new' href='".$get_ads->return_url."' title='".$get_ads->ads_title."'><img src='".$get_ads->ads_content."' style='max-height:269px'/></a>";
					return $html;
				}

				if($get_ads->ads_type=="swf"){
					$html='<embed style="max-height:269px" name="plugin" src="'.public_path().'/upload/ads/2OqU7KITyR.swf" type="application/x-shockwave-flash">';
					return $html;
				}
		}else{
			$html="<a target='_new' href='#' title=''><img src='".asset('public/assets/images/ads-here.jpg')."' /></a>";
			return $html;
		}
	}

	public static function get_standard_pornstar(){
		$get_ads= StandardAdsModel::where('position','=','pornstar')->take(1)->orderByRaw("RAND()")->first();
		if($get_ads!=NULL){
			if($get_ads->type=="upload"){
				$html="<a target='_new' href='".$get_ads->return_url."' title='".$get_ads->ads_title."'><img src='".$get_ads->ads_content."' style='max-height:269px'/></a>";
				return $html;
			}
			if($get_ads->type=="script_code"){
				$html='<div id="AD_ID">'.$get_ads->script_code.'</div>';
				return $html;
			}
			if($get_ads->ads_type=="swf"){
				$html="<a target='_new' href='".$get_ads->return_url."' title='".$get_ads->ads_title."'><img src='".$get_ads->ads_content."' style='max-height:269px'/></a>";
				return $html;
			}
		}else{
			$html="<a target='_new' href='#' title=''><img src='".asset('public/assets/images/ads-here.jpg')."' /></a>";
			return $html;
		}
	}

	public static function get_standard_video(){
		$get_ads= StandardAdsModel::where('position','=','video')->take(6)->orderByRaw("RAND()")->get();
		return $get_ads;
		// if(count($get_ads) > 0){

		// }else{
		// 	$html="<a target='_new' href='#' title=''><img src='".asset('public/assets/images/ads-here.jpg')."' /></a>";
		// 	return $html;
		// }
	}

	public static function get_standard_belove_video(){
		$get_ads= StandardAdsModel::where('position','=','under_video')->take(1)->orderByRaw("RAND()")->first();
		if($get_ads!=NULL){
			if($get_ads->type=="upload"){
				$html="<a target='_new' href='".$get_ads->return_url."' title='".$get_ads->ads_title."'><img  class='under_video_ads' src='".$get_ads->ads_content."' style='max-height:269px'/></a>";
				return $html;
			}
			if($get_ads->type=="script_code"){
				$html=$get_ads->script_code;
				return $html;
			}
			if($get_ads->ads_type=="swf"){
				$html="<a target='_new' href='".$get_ads->return_url."' title='".$get_ads->ads_title."'><img src='".$get_ads->ads_content."' style='max-height:269px'/></a>";
				return $html;
			}
		}else{
			$html="<a target='_new' href='#' title=''><img class='under_video_ads' src='".asset('public/assets/images/980x80.png')."' /></a>";
			return $html;
		}
	}
	
}
