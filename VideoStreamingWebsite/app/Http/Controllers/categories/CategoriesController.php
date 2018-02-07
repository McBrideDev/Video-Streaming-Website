<?php

namespace App\Http\Controllers\categories;

use App\Http\Controllers\Controller;
use App\Models\VideoModel;
use App\Models\CategoriesModel;
use App\Models\MemberVideoModel;
use App\Models\VideoCommentModel;
use App\Models\CountriesModel;
use App\Models\ChannelModel;
use App\Models\VideoCatModel;
use App\Helper\AppHelper;
use App\Helper\VideoHelper;
use DB;

class CategoriesController extends Controller {

	public function get_categories()  {
		$categories = AppHelper::getCategoryList();

		$countVideoGroupByCategory = VideoCatModel::select('cat_id', DB::raw('count(*) as total'))
						             ->groupBy('cat_id')
						             ->lists('total','cat_id');

		$recomment_categories = CategoriesModel::where('recomment', '=', 1)->where('status', '=', 1)->take(12)->OrderbyRaw('RAND()')->get();
		if ($categories) {
			return view('categories.index')->with('categories', $categories)
						->with('recomment_categories', $recomment_categories)
						->with('all_categories', $this->get_all_categories())
						->with('channel_popular', $this->get_channelpopular())
						->with('country_category', $this->get_country_category())
						->with('country', $this->get_country())
						->with('countVideoGroupByCategory', $countVideoGroupByCategory);
		}
	}

	public function get_all_categories() {
		$recomment_categories = CategoriesModel::where('status', '=', 1)->paginate(perPage());
		return $recomment_categories;
	}

	public function get_channelpopular() {
		$channelpopular = ChannelModel::take(10)
		->orderBy('total_view', 'DESC')
		// ->OrderbyRaw('RAND()')
		->get();
		return $channelpopular;
	}

	public function get_country_category() {
		$country_category = CategoriesModel::select('categories.*', 'countries.name', 'countries.id', 'countries.alpha_2')
							->join('countries', 'countries.id', '=', 'categories.contries_id')
							->OrderbyRaw('RAND()')
							->groupby('countries.id')
							->take(54)
							->get();

		return $country_category;
	}

	public function get_country() {
		$country = CountriesModel::get();
		return $country;
	}

	public function get_one_categories($id) {
		$categories = AppHelper::getCategoryList();

		$onecategoriesdetail = CategoriesModel::where('id', '=', $id)->first();

		$videoin = VideoModel::where('video.status', '=', '1')
			->join('video_cat', 'video_cat.video_id', '=', 'video.string_Id')
			->where("video_cat.cat_id", "=", "{$id}")
			// ->join(DB::raw("(select round(rand() * (select max(id) from `table_video`)) as id ) as r2"), 'video.id', '>=', DB::raw('r2.id'))
			// ->whereIn('video.string_Id', $videoIdsArr)
			->paginate(30);

		// $videoin = VideoModel::where('video.status', '=', '1')
		// 	->join('video_cat', 'video_cat.video_id', '=', 'video.string_Id')
		// 	->where("video_cat.cat_id", "=", "{$id}")
		// 	// ->join(DB::raw("(select round(rand() * (select max(id) from `table_video`)) as id ) as r2"), 'video.id', '>=', DB::raw('r2.id'))
		// 	// ->whereIn('video.string_Id', $videoIdsArr)
		// 	->paginate(30);

		if (count($videoin) > 0) {
			return view('categories.onecategories')->with('categories', $categories)
						->with('videoin', $videoin)
						// ->with('channel_popular', $this->get_channelpopular())
						// ->with('country_category', $this->get_country_category())
						->with('onecategoriesdetail', $onecategoriesdetail);
		} else {
			return view('categories.onecategories')->with('categories', $categories)
						->with('onecategoriesdetail', $onecategoriesdetail)
						// ->with('channel_popular', $this->get_channelpopular())
						// ->with('country_category', $this->get_country_category())
						->with('msg', trans('home.VIDEO_NOT_FOUND_FORM_CATEGORIES') . '. ' . trans('home.UPLOAD') . ' <a class="click-here" href="' . URL(getLang() . 'member-proflie.html?action=upload') . '">' . trans('home.CLICK_HERE') . '</a>');
		}
	}

	public function get_video_country($id) {
		$categories = AppHelper::getCategoryList();

		$country = CountriesModel::where('id', '=', $id)->first();
		$get_video = VideoModel::select('video.*', 'countries.name', 'categories.title_name as cat_name')
						->where('countries.id', '=', $id)
						->join('categories', 'categories.id', '=', 'video.categories_Id')
						->join('countries', 'countries.id', '=', 'categories.contries_id')
						->paginate(perPage());
		return view('categories.onecountry')->with('channel_popular', $this->get_channelpopular())
					->with('country_category', $this->get_country_category())
					->with('message', trans('home.VIDEO_NOT_FOUND'))
					->with('country', $country)
					->with('videoin', $get_video)
					->with('categories', $categories);
	}

	public function get_one_category_filter($id, $name, $action, $time) {
		switch ($action) {
			case 'new-video':
				return $this->category_filter_new($id, $action, $time);
				break;
			case 'most-favorited':
				return $this->category_filter_favorited($id, $action, $time);
				break;
			case 'most-rated':
				return $this->category_filter_rated($id, $action, $time);
				break;
			case 'most-viewed':
				return $this->category_filter_viewed($id, $action, $time);
				break;
			case 'most-commented':
				return $this->category_filter_commented($id, $action, $time);
				break;
			default:
				return $this->get_one_categories($id, $action, $time);
				break;
		}
	}

	public function category_filter_new($id, $action, $time) {
		if ($time == 'all') {
			$fist = 0;
			$end = 7200;
			$time = "";
			$time_name_lg = trans('home.ALL_DURATION');
			$time_name_xs = trans('home.ALL');
		}
		if ($time == "1-3") {
			$fist = 1;
			$end = 180;
			$time = "1-3";
			$time_name_lg = trans('home.SHORT_VIDEOS') . " (1-3min)";
			$time_name_xs = trans('home.SHORT_VIDEOS') . " (1-3min)";
		}
		if ($time == "3-10") {
			$fist = 181;
			$end = 600;
			$time = "3-10";
			$time_name_lg = trans('home.MEDIUM_VIDEOS') . "(3-10min)";
			$time_name_xs = trans('home.MEDIUM_VIDEOS') . "(3-10min)";
		}
		if ($time == "10+") {
			$fist = 601;
			$end = 7200;
			$time = "10+";
			$time_name_lg = trans('home.LONG_VIDEOS') . "(+10min)";
			$time_name_xs = trans('home.LONG_VIDEOS') . "(+10min)";
		}
		$categories = AppHelper::getCategoryList();

		$onecategoriesdetail = CategoriesModel::where('id', '=', $id)->first();
		$videoin = CategoriesModel::video_cat_order_new($id, $fist, $end);

		if (count($videoin) > 0) {
			return view('categories.onecategories')->with('categories', $categories)
						->with('videoin', $videoin)
						->with('filter_title_lg', trans('home.NEWEST_VIDEOS'))
						->with('filter_title_xs', trans('home.NEWEST_VIDEOS'))
						->with('filter_time_lg', $time_name_lg)
						->with('filter_time_xs', $time_name_xs)
						->with('channel_popular', $this->get_channelpopular())
						->with('country_category', $this->get_country_category())
						->with('onecategoriesdetail', $onecategoriesdetail);
		} else {
			return view('categories.onecategories')->with('categories', $categories)
						->with('onecategoriesdetail', $onecategoriesdetail)
						->with('channel_popular', $this->get_channelpopular())
						->with('filter_title_lg', trans('home.NEWEST_VIDEOS'))
						->with('filter_title_xs', trans('home.NEWEST_VIDEOS'))
						->with('filter_time_lg', $time_name_lg)
						->with('filter_time_xs', $time_name_xs)
						->with('country_category', $this->get_country_category())
						->with('msg', trans('home.VIDEO_NOT_FOUND_FORM_CATEGORIES') . '. ' . trans('home.UPLOAD') . ' <a class="click-here" href="' . URL(getLang() . 'member-proflie.html?action=upload') . '">' . trans('home.CLICK_HERE') . '</a>');
		}
	}

	public function category_filter_favorited($id, $action, $time) {

		if ($time == 'all') {
			$fist = 0;
			$end = 7200;
			$time = "";
			$time_name_lg = trans('home.ALL_DURATION');
			$time_name_xs = trans('home.ALL');
		}
		if ($time == "1-3") {
			$fist = 1;
			$end = 180;
			$time = "1-3";
			$time_name_lg = trans('home.SHORT_VIDEOS') . " (1-3min)";
			$time_name_xs = trans('home.SHORT_VIDEOS') . " (1-3min)";
		}
		if ($time == "3-10") {
			$fist = 181;
			$end = 600;
			$time = "3-10";
			$time_name_lg = trans('home.MEDIUM_VIDEOS') . "(3-10min)";
			$time_name_xs = trans('home.MEDIUM_VIDEOS') . "(3-10min)";
		}
		if ($time == "10+") {
			$fist = 601;
			$end = 7200;
			$time = "10+";
			$time_name_lg = trans('home.LONG_VIDEOS') . "(+10min)";
			$time_name_xs = trans('home.LONG_VIDEOS') . "(+10min)";
		}
		$categories = AppHelper::getCategoryList();

		$onecategoriesdetail = CategoriesModel::where('id', '=', $id)->first();

		$get_favorite = MemberVideoModel::get();
		$list_temp = array();
		for ($i = 0; $i < count($get_favorite); $i++) {
			if (!in_array($get_favorite[$i]->video_Id, $list_temp)) {
				array_push($list_temp, $get_favorite[$i]->video_Id);
			}
		}
		$new_array = implode(',', $list_temp);
		$array = explode(',', $new_array);
		$listvideo_temp = array_unique($array);
		$new_list = implode(',', $listvideo_temp);

		$video_id = array();
		$get_video_id = \App\Models\VideoCatModel::where('cat_id', '=', $id)->get();
		for ($i = 0; $i < count($get_video_id); $i++) {
			array_push($video_id, $get_video_id[$i]->video_id);
		}

		$videoin = VideoModel::where('status', '=', 1)
						->whereRaw("duration BETWEEN " . $fist . " and " . $end . "")
						->whereIn('string_Id', $video_id)
						->whereIn('string_Id', $listvideo_temp)
						->paginate(perPage());

		// $videoin =VideoModel::where('status','=',1)
		// ->whereIn('string_Id',$listvideo_temp)
		// ->where('categories_Id','=',$id)
		// ->Orderby('title_name','DESC')->paginate(20);

		if (count($videoin) > 0) {
			return view('categories.onecategories')->with('categories', $categories)
											->with('videoin', $videoin)
											->with('channel_popular', $this->get_channelpopular())
											->with('country_category', $this->get_country_category())
											->with('filter_title_lg', trans('home.MOST_FAVORITED_VIDEO'))
											->with('filter_title_xs', trans('home.FAVORITED'))
											->with('filter_time_lg', $time_name_lg)
											->with('filter_time_xs', $time_name_xs)
											->with('onecategoriesdetail', $onecategoriesdetail)
											->with('hidden_action', $action);
		} else {
			return view('categories.onecategories')->with('categories', $categories)
						->with('onecategoriesdetail', $onecategoriesdetail)
						->with('channel_popular', $this->get_channelpopular())
						->with('filter_title_lg', trans('home.MOST_FAVORITED_VIDEO'))
						->with('filter_title_xs', trans('home.FAVORITED'))
						->with('filter_time_lg', $time_name_lg)
						->with('filter_time_xs', $time_name_xs)
						->with('country_category', $this->get_country_category())
						->with('hidden_action', $action)
						->with('msg', trans('home.VIDEO_NOT_FOUND_FORM_CATEGORIES') . '. ' . trans('home.UPLOAD') . ' <a class="click-here" href="' . URL(getLang() . 'member-proflie.html?action=upload') . '">' . trans('home.CLICK_HERE') . '</a>');
		}
	}

	public function category_filter_rated($id, $action, $time) {
		if ($time == 'all') {
			$fist = 0;
			$end = 7200;
			$time = "";
			$time_name_lg = trans('home.ALL_DURATION');
			$time_name_xs = trans('home.ALL');
		}
		if ($time == "1-3") {
			$fist = 1;
			$end = 180;
			$time = "1-3";
			$time_name_lg = trans('home.SHORT_VIDEOS') . " (1-3min)";
			$time_name_xs = trans('home.SHORT_VIDEOS') . " (1-3min)";
		}
		if ($time == "3-10") {
			$fist = 181;
			$end = 600;
			$time = "3-10";
			$time_name_lg = trans('home.MEDIUM_VIDEOS') . "(3-10min)";
			$time_name_xs = trans('home.MEDIUM_VIDEOS') . "(3-10min)";
		}
		if ($time == "10+") {
			$fist = 601;
			$end = 7200;
			$time = "10+";
			$time_name_lg = trans('home.LONG_VIDEOS') . "(+10min)";
			$time_name_xs = trans('home.LONG_VIDEOS') . "(+10min)";
		}
		$categories = AppHelper::getCategoryList();

		$onecategoriesdetail = CategoriesModel::where('id', '=', $id)->first();
		$videoin = CategoriesModel::video_cat_order_rate($id, $fist, $end);
		if (count($videoin) > 0) {
			return view('categories.onecategories')->with('categories', $categories)
											->with('videoin', $videoin)
											->with('channel_popular', $this->get_channelpopular())
											->with('country_category', $this->get_country_category())
											->with('filter_title_lg', trans('home.TOP_RATE_VIDEOS'))
											->with('filter_title_xs', trans('home.RATED'))
											->with('filter_time_lg', $time_name_lg)
											->with('filter_time_xs', $time_name_xs)
											->with('hidden_action', $action)
											->with('onecategoriesdetail', $onecategoriesdetail);
		} else {
			return view('categories.onecategories')->with('categories', $categories)
											->with('onecategoriesdetail', $onecategoriesdetail)
											->with('channel_popular', $this->get_channelpopular())
											->with('filter_title_lg', trans('home.TOP_RATE_VIDEOS'))
											->with('filter_title_xs', trans('home.RATED'))
											->with('filter_time_lg', $time_name_lg)
											->with('filter_time_xs', $time_name_xs)
											->with('hidden_action', $action)
											->with('country_category', $this->get_country_category())
											->with('msg', trans('home.VIDEO_NOT_FOUND_FORM_CATEGORIES') . '. ' . trans('home.UPLOAD') . ' <a class="click-here" href="' . URL(getLang() . 'member-proflie.html?action=upload') . '">' . trans('home.CLICK_HERE') . '</a>');
		}
	}

	public function category_filter_viewed($id, $action, $time) {
		if ($time == 'all') {
			$fist = 0;
			$end = 7200;
			$time = "";
			$time_name_lg = trans('home.ALL_DURATION');
			$time_name_xs = trans('home.ALL');
		}
		if ($time == "1-3") {
			$fist = 1;
			$end = 180;
			$time = "1-3";
			$time_name_lg = trans('home.SHORT_VIDEOS') . " (1-3min)";
			$time_name_xs = trans('home.SHORT_VIDEOS') . " (1-3min)";
		}
		if ($time == "3-10") {
			$fist = 181;
			$end = 600;
			$time = "3-10";
			$time_name_lg = trans('home.MEDIUM_VIDEOS') . "(3-10min)";
			$time_name_xs = trans('home.MEDIUM_VIDEOS') . "(3-10min)";
		}
		if ($time == "10+") {
			$fist = 601;
			$end = 7200;
			$time = "10+";
			$time_name_lg = trans('home.LONG_VIDEOS') . "(+10min)";
			$time_name_xs = trans('home.LONG_VIDEOS') . "(+10min)";
		}
		$categories = AppHelper::getCategoryList();

		$onecategoriesdetail = CategoriesModel::where('id', '=', $id)->first();

		$videoList = VideoModel::where('status', '=', 1)->get(); //get array list cat_id
		//$cat_video_id = get_cat_video_id($id, $videoList); //call helper
		$videoin = CategoriesModel::video_cat_order_viewed($id, $fist, $end);

		if (count($videoin) > 0) {
			return view('categories.onecategories')->with('categories', $categories)
						->with('videoin', $videoin)
						->with('channel_popular', $this->get_channelpopular())
						->with('filter_title_lg', trans('home.MOST_VIEWED_VIDEO'))
						->with('filter_title_xs', trans('home.VIEWS'))
						->with('filter_time_lg', $time_name_lg)
						->with('filter_time_xs', $time_name_xs)
						->with('hidden_action', $action)
						->with('country_category', $this->get_country_category())
						->with('onecategoriesdetail', $onecategoriesdetail);
		} else {
			return view('categories.onecategories')->with('categories', $categories)
						->with('onecategoriesdetail', $onecategoriesdetail)
						->with('channel_popular', $this->get_channelpopular())
						->with('filter_title_lg', trans('home.MOST_VIEWED_VIDEO'))
						->with('filter_title_xs', trans('home.VIEWS'))
						->with('filter_time_lg', $time_name_lg)
						->with('filter_time_xs', $time_name_xs)
						->with('hidden_action', $action)
						->with('country_category', $this->get_country_category())
						->with('msg', trans('home.VIDEO_NOT_FOUND_FORM_CATEGORIES') . '. ' . trans('home.UPLOAD') . ' <a class="click-here" href="' . URL(getLang() . 'member-proflie.html?action=upload') . '">' . trans('home.CLICK_HERE') . '</a>');
		}
	}

	public function category_filter_commented($id, $action, $time) {
		if ($time == 'all') {
			$fist = 0;
			$end = 7200;
			$time = "";
			$time_name_lg = trans('home.ALL_DURATION');
			$time_name_xs = trans('home.ALL');
		}
		if ($time == "1-3") {
			$fist = 1;
			$end = 180;
			$time = "1-3";
			$time_name_lg = trans('home.SHORT_VIDEOS') . " (1-3min)";
			$time_name_xs = trans('home.SHORT_VIDEOS') . " (1-3min)";
		}
		if ($time == "3-10") {
			$fist = 181;
			$end = 600;
			$time = "3-10";
			$time_name_lg = trans('home.MEDIUM_VIDEOS') . "(3-10min)";
			$time_name_xs = trans('home.MEDIUM_VIDEOS') . "(3-10min)";
		}
		if ($time == "10+") {
			$fist = 601;
			$end = 7200;
			$time = "10+";
			$time_name_lg = trans('home.LONG_VIDEOS') . "(+10min)";
			$time_name_xs = trans('home.LONG_VIDEOS') . "(+10min)";
		}
		$categories = AppHelper::getCategoryList();

		$onecategoriesdetail = CategoriesModel::where('id', '=', $id)->first();

		$get_comment = VideoCommentModel::Groupby('video_Id')->get();
		$list_comment_array = array();
		for ($i = 0; $i < count($get_comment); $i++) {
			array_push($list_comment_array, $get_comment[$i]->video_Id);
		}

		$video_id = array();

		$get_video_id = \App\Models\VideoCatModel::where('cat_id', '=', $id)->get();
		for ($i = 0; $i < count($get_video_id); $i++) {
			array_push($video_id, $get_video_id[$i]->video_id);
		}

		$videoin = VideoModel::where('status', '=', 1)
						->whereRaw("duration BETWEEN " . $fist . " and " . $end . "")
						->whereIn('string_Id', $video_id)
						->whereIn('string_Id', $list_comment_array)
						->Orderby('title_name', 'DESC')
						->paginate(perPage());

		if (count($videoin) > 0) {
			return view('categories.onecategories')->with('categories', $categories)
						->with('videoin', $videoin)
						->with('channel_popular', $this->get_channelpopular())
						->with('filter_title_lg', trans('home.MOST_COMMENTED_VIDEO'))
						->with('filter_title_xs', trans('home.COMMENTED'))
						->with('filter_time_lg', $time_name_lg)
						->with('filter_time_xs', $time_name_xs)
						->with('hidden_action', $action)
						->with('country_category', $this->get_country_category())
						->with('onecategoriesdetail', $onecategoriesdetail);
		}
		return view('categories.onecategories')->with('categories', $categories)
					->with('onecategoriesdetail', $onecategoriesdetail)
					->with('channel_popular', $this->get_channelpopular())
					->with('filter_title_lg', trans('home.MOST_COMMENTED_VIDEO'))
					->with('filter_title_xs', trans('home.COMMENTED'))
					->with('filter_time_lg', $time_name_lg)
					->with('filter_time_xs', $time_name_xs)
					->with('hidden_action', $action)
					->with('country_category', $this->get_country_category())
					->with('msg', trans('home.VIDEO_NOT_FOUND_FORM_CATEGORIES') . '. ' . trans('home.UPLOAD') . ' <a class="click-here" href="' . URL(getLang() . 'member-proflie.html?action=upload') . '">' . trans('home.CLICK_HERE') . '</a>');
	}

}
