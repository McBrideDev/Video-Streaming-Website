<?php

namespace App\Http\Controllers\admincp;
use App\Http\Controllers\Controller;
use App\Services\Modules\Modules;
use App\Models\ChannelModel;
use App\Models\ChanneSubscriberModel;
use App\Models\CategoriesModel;
use App\Models\ActivityLogModel;
use App\Models\MSGPrivateModel;
use App\Models\MemberModel;
use App\Models\StandardAdsModel;
use App\Models\VideoTextAdsModel;
use App\Models\VideoAdsModel;
use App\Models\VideoModel;
use App\Models\VideoCommentModel;
use App\Models\ProfileCommentModel;
use App\Models\CountReportModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
class DashboardController extends Controller
{

	public function get_dashboard(){
		return view('admincp.dashboard.index')
		->with('total',$this->get_total_video())
		->with('new_video',$this->get_new_video())
		->with('video_featured',$this->get_video_featured())
		->with('video_validation',$this->get_video_validation())
		->with('categories',$this->get_categories_report())
		->with('msgprivate',$this->get_msg_report())
		->with('member',$this->get_member_last())
		->with('videocomment',$this->get_video_comment())
		->with('profilecomment',$this->get_profile_comment())
		->with('membercount',$this->get_member())
		->with('countvideoreport',$this->get_video_comment_report())
		->with('countprofilereport',$this->get_profile_report())
		->with('get_standard_ads',$this->get_standard_ads())
		->with('get_text_ads',$this->get_text_ads())
		->with('get_video_ads',$this->get_video_ads());
	}

	public function get_categories_report(){
		$categories = CategoriesModel::count();
		if($categories>0){
			return $categories;
		}else{
			return 0;
		}

	}

	public function get_msg_report(){
		$count = MSGPrivateModel::count();
		if($count>0){
			return $count;
		}else{
			return 0;
		}
	}
	public function get_member(){
		$membercount= MemberModel::where('email', '!=', 'long.it.stu@gmail.com')->where('username', '!=', 'admin')->count();
		if($membercount>0){
			return $membercount;
		}else{
			return 0;
		}

	}
	public function get_member_last(){
		$member= MemberModel::where('email', '!=', 'long.it.stu@gmail.com')->take(5)->Orderby('created_at', 'DESC')->get();
		if(count($member)>0){
			return $member;
		}
	}

	public function get_video_validation(){
		$video_validation = VideoModel::where('status', '=', VideoModel::INACTIVE)->count();
		if($video_validation>0){
			return $video_validation;
		}else{
			return 0;
		}
	}
	public function get_video_featured(){
		$video_featured= VideoModel::where('featured', '=', VideoModel::FEATURED)->count();
		if($video_featured>0){
			return $video_featured;
		}else{
			return 0;
		}

	}
	public function get_new_video(){
		$new_video =VideoModel::take(10)->Orderby('created_at', 'desc')->count();
		if($new_video>0){
			return $new_video;
		}else{
			return 0;
		}

	}
	public function get_total_video(){
		$total= VideoModel::count();
		if($total>0){
			return $total;
		}else{
			return 0;
		}
	}
	public function get_video_comment(){
		$videocomment = VideoCommentModel::select('video_comment.*', 'video.string_Id')->join('video', 'video.string_id','=', 'video_comment.video_Id')->count();
		if($videocomment>0){
			return $videocomment;
		}else{
			return 0;
		}

	}
	public function get_video_comment_report(){
		$countvideoreport = CountReportModel::where('report_status', '=', 1)->count();
		if($countvideoreport>0){
			return $countvideoreport;
		}else{
			return 0;
		}
	}
	public function get_profile_comment(){
		$profilecomment = ProfileCommentModel::count();
		if($profilecomment>0){
			return $profilecomment;
		}else{
			return 0;
		}

	}

	public function get_profile_report(){
		$countprofilereport = CountReportModel::where('report_status','=',2)->count();
		if($countprofilereport>0){
			return $countprofilereport;
		}else{
			return 0;
		}

	}
	public function get_standard_ads(){
		$standard=StandardAdsModel::count();
		if($standard>0){
			return $standard;
		}else{
			return 0;
		}
	}
	public function get_text_ads(){
		$VideoTextAds= VideoTextAdsModel::count();
		if($VideoTextAds>0){
			return $VideoTextAds;
		}else{
			return 0;
		}
	}
	public function get_video_ads(){
		$VideoAds= VideoAdsModel::count();
		if($VideoAds>0){
			return $VideoAds;
		}else{
			return 0;
		}
	}
}
