<?php
namespace App\Http\Controllers\toprate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\Modules\Modules;
use App\Models\ActivityLogModel;
use App\Models\ChannelModel;
use App\Models\VideoModel;
use App\Models\CountriesModel;
use App\Models\RatingModel;
use App\Models\ChanneSubscriberModel;
use App\Models\CategoriesModel;
use Illuminate\Http\Request as Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
class TopRateController extends Controller
{
	public function get_videotoprate(){
		$toprate = VideoModel::where('status','=','1')
				->orderby('rating','DESC')->paginate(38);

		return view('toprate.toprate')
			->with('toprate',$toprate);
	}

	public function get_top_rate_filter($date,$time){
		$compare = "=";
		if ($date == 'today') {

			// $time="all";
		}
		if ($time == 'all') {
			$fist = 0;
			$end = 7200;
			$time ="";
			$time_name = trans('home.ALL_TIMES');
		}
		if ($time== "1-3") {
			$fist = 1;
			$end = 180;
			$time ="1-3";
			$time_name = trans('home.VIDEOS')." (1-3min)";
		}
		if ($time== "3-10") {
			$fist = 181;
			$end = 600;
			$time ="3-10";
			$time_name = trans('home.VIDEOS')." (3-10min)";
		}
		if ($time== "10+") {
			$fist = 601;
			$end = 7200;
			$time ="10+";
			$time_name = trans('home.VIDEOS')." (10+min)";
		}
		if($date!="today"){
			if($date=="week"){
				$lastweek=date_create('Sunday last week');
				$thisweek=date_create('Sunday this week');
				$toprate = VideoModel::whereRaw("updated_at BETWEEN '".get_object_vars($lastweek)['date']."' and '".get_object_vars($thisweek)['date']."'  and duration BETWEEN ".$fist." and ".$end."")
											->orderby('rating','DESC')
											->paginate(38);
			}
			if($date=="month"){
				$toprate = VideoModel::whereRaw("duration BETWEEN ".$fist." and ".$end."")
						->where(DB::raw('MONTH(updated_at)'), '=', date('n'))
									->orderby('rating','DESC')
									->paginate(38);
			}
			if($date=="all"){
				$toprate = VideoModel::whereRaw("duration BETWEEN ".$fist." and ".$end."")
				->where('status','=','1')
				->orderby('rating','DESC')->paginate(38);
			}
		} else {
			$toprate = VideoModel::select('video.*','rating.like','rating.dislike','rating.string_id as video_id','rating.created_at as rated_at')
						->whereBetween('video.duration',array($fist,$end))
						->where('rating.created_at', 'like', ''.date('Y-m-d').'%' )
						->join('rating','rating.string_id','=','video.string_Id')
						->orderby('video.rating','DESC')
						->groupBy('rating.ID')->paginate(38);

		}

		if(\Request::ajax()){
			if($toprate){
				return view('toprate.toprate-fillter')->with('toprate',$toprate)->with('date',$date)->with('time',$time_name)->with('data_time',$time);
			}
		}
		return view('toprate.toprate')->with('toprate',$toprate)->with('date',$date)->with('time',$time_name)->with('data_time',$time);
	}
}
