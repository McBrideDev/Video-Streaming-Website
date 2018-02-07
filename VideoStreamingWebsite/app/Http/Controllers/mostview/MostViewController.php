<?php
namespace App\Http\Controllers\mostview;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\Modules\Modules;
use App\Models\ActivityLogModel;
use App\Models\ChannelModel;
use App\Models\VideoModel;
use App\Models\CountriesModel;
use App\Models\ChanneSubscriberModel;
use App\Models\CategoriesModel;
use Illuminate\Http\Request as Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;

class MostViewController extends Controller {
	public function get_videomostview(){
		$mostview = VideoModel::where('status','=','1')
				->orderby('total_view','DESC')->paginate(38);

		$countries = CountriesModel::select('countries.*')
				->join('categories', 'countries.id', '=', 'categories.contries_id')
				->join('video', 'video.categories_Id', '=', 'categories.ID')
				->groupBy('countries.id')->get();
		//echo  var_dump($channelsubscriber);
		$categories = CategoriesModel::where('status','=','1')
										->orderby('title_name','asc')
										->get();
		if($categories){
				return view('mostview.mostview')->with('categories',$categories)
			 ->with('mostview',$mostview)
			 ->with('countries',$countries);
		}
	}

	public function get_videomostview_find($date,$time) {
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
		if($date!="today") {
			if($date=="week"){
				$lastweek=date_create('Sunday last week');
				$thisweek=date_create('Sunday this week');
				$mostview =  VideoModel::whereRaw("updated_at BETWEEN '".get_object_vars($lastweek)['date']."' and '".get_object_vars($thisweek)['date']."'  and duration BETWEEN ".$fist." and ".$end."")
												->orderby('total_view','DESC')
												->where('status','=','1')
												->paginate(38);
			}
			if($date=="month"){
				$mostview = VideoModel::whereRaw("duration BETWEEN ".$fist." and ".$end."")
						->where(DB::raw('MONTH(updated_at)'), '=', date('n'))
						->where('status','=','1')
									->orderby('total_view','DESC')
									->paginate(38);
			}
			if($date=="all"){
				$mostview = VideoModel::whereRaw("duration BETWEEN ".$fist." and ".$end."")
				->where('status','=','1')
				->orderby('total_view','DESC')
				->paginate(38);
			}

		} else {
			$mostview = VideoModel::whereBetween('duration',array($fist,$end))
						->where('updated_at', 'like', ''.date('Y-m-d').'%' )
						->orderby('total_view','DESC')
						->groupBy('ID')->paginate(38);
		}

		if(\Request::ajax()){
			if($mostview){
				return view('mostview.mostview_find')->with('mostview',$mostview)->with('date',$date)->with('time',$time_name)->with('data_time',$time);
			}
		}
		return view('mostview.mostview')->with('mostview',$mostview)->with('date',$date)->with('time',$time_name)->with('data_time',$time);
	}

}
