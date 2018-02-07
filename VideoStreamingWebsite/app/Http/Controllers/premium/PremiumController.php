<?php

namespace App\Http\Controllers\premium;

use App\Models\VideoModel;
use App\Models\CategoriesModel;
use App\Http\Controllers\Controller;
use App\Helper\AppHelper;

class PremiumController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

		$count = VideoModel::where('status', '=', '1')->where('buy_this', '=', '1')->count();
		$skip = 4;
		$limit = $count;
		if ($count > $skip) {
			$limit = $count - $skip;
		}

		$premium = VideoModel::where('status', '=', '1')
						->where('buy_this', '=', '1')
						->orderby('rating', 'DESC')
						->orderby('total_view', 'DESC')
						->paginate(perPage());
		$categories = AppHelper::getCategoryList();
		return view('premium.index')
					->with('categories', $categories)
					->with('premium', $premium);
	}

	public function get_sort($sort) {
		$categories = AppHelper::getCategoryList();

		if ($sort == "new-video") {
			$filter_title_lg = trans('home.NEWEST_VIDEOS');
			$filter_title_xs = trans('home.NEWEST_VIDEOS');
			$count = VideoModel::where('status', '=', '1')->where('buy_this', '=', '1')->count();
			$skip = 4;
			$limit = $count;
			if ($count > $skip) {
				$limit = $count - $skip;
			}
			$premium = VideoModel::where('status', '=', '1')
							->where('buy_this', '=', '1')
							->orderby('created_at', 'DESC')
							->skip($skip)
							->take($limit)
							->paginate(perPage());

			return view('premium.index')
						->with('categories', $categories)
						->with('premium', $premium)->with('filter_title_lg', $filter_title_lg)
						->with('filter_title_xs', $filter_title_xs);
		}
		if ($sort == "most-rated") {
			$filter_title_lg = trans('home.TOP_RATE_VIDEOS');
			$filter_title_xs = trans('home.RATED');
			$count = VideoModel::where('status', '=', '1')->where('buy_this', '=', '1')->count();
			$skip = 4;
			$limit = $count;
			if ($count > $skip) {
				$limit = $count - $skip;
			}
			$premium = VideoModel::where('status', '=', '1')
							->where('buy_this', '=', '1')
							->orderby('rating', 'DESC')
							->skip($skip)
							->take($limit)
							->paginate(perPage());

			return view('premium.index')
						->with('categories', $categories)
						->with('premium', $premium)
						->with('filter_title_lg', $filter_title_lg)
						->with('filter_title_xs', $filter_title_xs);
		}
		if ($sort == "most-viewed") {
			$filter_title_lg = trans('home.MOST_VIEWED_VIDEO');
			$filter_title_xs = trans('home.VIEWS');
			$count = VideoModel::where('status', '=', '1')->where('buy_this', '=', '1')->count();
			$skip = 4;
			$limit = $count;
			if ($count > $skip) {
				$limit = $count - $skip;
			}
			$premium = VideoModel::where('status', '=', '1')
							->where('buy_this', '=', '1')
							->orderby('total_view', 'DESC')
							->skip($skip)
							->take($limit)
							->paginate(perPage());

			return view('premium.index')
						->with('categories', $categories)
						->with('premium', $premium)->with('filter_title_lg', $filter_title_lg)
						->with('filter_title_xs', $filter_title_xs);
		}
	}

}
