<?php

namespace App\Http\Controllers\channel;

use App\Http\Controllers\Controller;
use App\Models\ChannelModel;
use App\Models\VideoModel;
use App\Models\ChanneSubscriberModel;
use App\Models\SubsriptionModel;
use App\Helper\AppHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request as Request;

class ChannelController extends Controller {

	public function get_channel() {
		$countVideoBaseChannelId = VideoModel::select('channel_Id', DB::raw('count(*) as total'))
							->where('channel_Id', '!=', '')
							->groupBy('channel_Id')
							->lists('total','channel_Id');

		$categories = AppHelper::getCategoryList();
		return view('channel.channel')->with('categories', $categories)
						->with('channel_recently', $this->getChannelRecently())
						->with('channelsubscriber', $this->get_channelsubscriber())
						->with('channelpopular', $this->get_channelpopular())
						->with('channeltoprate', $this->get_channeltoprate())
						->with('channelmostview', $this->get_channelmostview())
						->with('countVideoBaseChannelId', $countVideoBaseChannelId);
	}

	public function channel_recently() {
		$count = ChannelModel::where('status', '=', '1')->count();
		$skip = 4;
		$limit = $count - $skip;
		$channels = ChannelModel::where('status', '=', '1')
						->orderby('created_at', 'DESC')
						->OrderbyRaw('RAND()')
						->skip($skip)
						->take($limit)
						->paginate(perPage());
		$categories = AppHelper::getCategoryList();
		if ($categories) {
			return view('channel.channel_recently')
							->with('categories', $categories)
							->with('channels', $channels);
		}
	}

	public function channel_subscriber() {
		$count = ChannelModel::select('channel.*', 'channel_subscriber.member_Id')
						->where('channel.status', '=', '1')
						->join('channel_subscriber', 'channel_subscriber.channel_Id', '=', 'channel.id')
						->Orderby('channel_subscriber.member_Id', 'DESC')->count();

		$skip = 4;
		$limit = $count - $skip;

		$channels = ChannelModel::select('channel.*', 'channel_subscriber.member_Id')
						->where('channel.status', '=', '1')
						->join('channel_subscriber', 'channel_subscriber.channel_Id', '=', 'channel.id')
						->Orderby('channel_subscriber.member_Id', 'DESC')
						->skip($skip)
						->take($limit)
						->paginate(perPage());
		$categories = AppHelper::getCategoryList();
		if ($categories) {
			return view('channel.channel_subscriber')
						->with('categories', $categories)
						->with('channels', $channels);
		}
	}

	public function channel_popular() {
		$count = ChannelModel::where('status', '=', '1')
						->orderBy('total_view', 'DESC')
						->count();
		$skip = 4;
		$limit = $count - $skip;

		$channels = ChannelModel::where('status', '=', '1')
						->orderBy('total_view', 'DESC')
						->skip($skip)
						->take($limit)
						->paginate(perPage());
		$categories = AppHelper::getCategoryList();
		if ($categories) {
			return view('channel.channel_popular')
							->with('categories', $categories)
							->with('channels', $channels);
		}
	}

	public function getChannelRecently() {
		return ChannelModel::select('channel.*', DB::raw('count(table_video.channel_Id) as video_numbers'))
						->leftJoin('video', 'channel.id', '=', 'video.channel_Id')
						->where('channel.status', '=', '1')
						->take(8)
						->OrderbyRaw('RAND()')
						->get();
	}

	public function get_channelsubscriber() {
		return ChannelModel::select('channel.*', 'channel_subscriber.member_Id', DB::raw('(SELECT count(table_video.id) FROM table_video WHERE table_video.channel_Id = table_channel.id) as video_numbers'))
						->where('channel.status', '=', '1')
						->join('channel_subscriber', 'channel_subscriber.channel_Id', '=', 'channel.id')
						->take(12)
						->orderBy('channel_subscriber.member_Id', 'DESC')
						->get();
	}

	public function get_channelpopular() {
		$channelpopular = ChannelModel::select('channel.*', DB::raw('(SELECT count(table_video.id) FROM table_video WHERE table_video.channel_Id = table_channel.id) as video_numbers'))
						->where('channel.status', '=', '1')
						->take(12)
						->orderBy('total_view', 'DESC')
						->get();
		return $channelpopular;
	}

	public function get_channeltoprate() {


		$channeltoprate = DB::select(DB::Raw("SELECT
			COUNT(table_video.id) AS totalvideo,
			table_channel.id,
			table_channel.title_name,
			table_channel.post_name,
			table_channel.poster,
			table_video.string_Id,
			table_video.rating,
			table_video.channel_Id,
			table_video.video_src

			FROM
			table_video ,
			table_channel
			WHERE
			table_video.`status` = 1

			and table_video.channel_Id=table_channel.id

			GROUP BY
			table_video.channel_Id
			ORDER BY table_video.rating  DESC"));

		return $channeltoprate;
	}

	public function get_channelmostview() {
		$channelmostview = DB::select(DB::Raw("SELECT
			COUNT(table_video.id) AS totalvideo,
			table_channel.id,
			table_channel.title_name,
			table_channel.post_name,
			table_channel.poster,
			table_video.string_Id,
			table_video.total_view,
			table_video.channel_Id,
			table_video.video_src
			FROM
			table_video ,
			table_channel

			WHERE
			table_video.`status` = 1
			and table_video.channel_Id=table_channel.id

			GROUP BY
			table_video.channel_Id
			ORDER BY table_video.total_view  DESC"));


		return $channelmostview;
	}

	public function get_channel_video($id) {
		if ($id != NULL) {
			$categories = AppHelper::getCategoryList();
			$get_channel = ChannelModel::where('id', '=', $id)->first();
			$visit = \Session::get('visit_channel');
			if ($visit[0]['id'] != $id) {
				$token_visit = array('is_visit' => 0, 'id' => $id);
				\Session::remove("visit_channel", $token_visit);
				\Session::push("visit_channel", $token_visit);

				$updateview = ChannelModel::where('id', '=', $visit[0]['id'])
								->update(array('total_view' => $get_channel->total_view + 1));
			}

			$get_related_channel = ChannelModel::where('id', '<>', $id)->take(4)->orderBy(DB::raw('RAND()'))->get();

			$getvideo = VideoModel::where('channel_Id', '=', $id)->Orderby('total_view', 'desc')->paginate(36);

			return view('channel.onechannel')
						->with('channelvideo', $getvideo)
						->with('categories', $categories)
						->with('related', $get_related_channel)
						->with('channel', $get_channel)
						->with('popular', $this->get_video_channel_popular($id))
						->with('totalvideo', $this->get_channel_total_video($id))
						->with('subscriber', $this->get_total_subscriber_channel($id))
						->with('check_subscriber', $this->get_subscriber_show($get_channel->id))
						->with('checkBuyChannel', $this->checkBuyChannel($get_channel->id));
		} else {
			return redirect('/');
		}
	}

	public function get_video_channel_popular($id) {

		$get_video = VideoModel::where('channel_Id', '=', $id)->Orderby('total_view', 'desc')->first();
		return $get_video;
	}

	public function checkBuyChannel($id)
	{
		$payment = SubsriptionModel::where('channel_id', '=', $id)->first();
		return empty($payment) ? false : true;
	}

	public function get_channel_total_video($id) {
		$total = VideoModel::where('channel_Id', '=', $id)->count();
		return $total;
	}

	public function get_total_subscriber_channel($id) {
		$subscriber = ChanneSubscriberModel::where('channel_Id', '=', $id)->first();
		if ($subscriber != NULL) {
			$total = explode(',', $subscriber->member_Id);
			return $total;
		} else {
			return NULL;
		}
	}

	public function get_subscriber($channelid) {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$checkchannel = ChanneSubscriberModel::where('channel_Id', '=', $channelid)->first();
			if ($checkchannel != NULL) {
				$memberid = explode(",", $checkchannel->member_Id);
				if (in_array($user->id, $memberid)) {
					return 2; //already
				} else {
					$update_subscriber = ChanneSubscriberModel::where('channel_Id', '=', $channelid)
									->update(array('member_Id' => $checkchannel->member_Id . ',' . $user->id, 'status' => 1));
					if ($update_subscriber) {
						return 3; //update add member
					}
				}
			} else {
				$insert_subscriber = new ChanneSubscriberModel();
				$insert_subscriber->Channel_Id = $channelid;
				$insert_subscriber->member_Id = $user->id;
				$insert_subscriber->status = 1;
				if ($insert_subscriber->save()) {
					return 1; //add new first
				}
			}
		}
		return 0;
	}

	public function get_subscriber_show($channelid) {
		if (\Session::has('User')) {
			$user = \Session::get('User');
			$checkchannel = ChanneSubscriberModel::where('channel_Id', '=', $channelid)->first();

			if ($checkchannel != NULL) {

				$memberid = explode(",", $checkchannel->member_Id);
				if (in_array($user->id, $memberid)) {
					return trans('home.SUBSCRIBED');
				} else {
					return trans('home.SUBSCRIBE');
				}
			}
		} else {
			return trans('home.SUBSCRIBE');
		}
	}

	public function get_filter($key) {
		if (!empty($key)) {

			$get_channel = ChannelModel::where(function($q) use ($key) {
				if($key=='specialKey') {
					$q->whereRaw("title_name REGEXP '^[^A-Za-z]'");
				} else {
					$q->where('title_name', 'like', '' . $key . '%');
				}
			})->paginate(perPage());

			if (count($get_channel) > 0) {
				return view('channel.channelfilter')->with('filter', $get_channel)->with('key', $key);
			}
			return view('channel.channelfilter')->with('msg_filter', trans('home.CHANNEL_FILLTER_NOT_FOUND'));
		}
		return 0;
	}

	public function get_view_video_channel($id, $string_id) {

		if ($id != NULL && $string_id != NULL) {
			$categories = AppHelper::getCategoryList();
			$get_channel = ChannelModel::where('id', '=', $id)->first();
			$get_related_channel = ChannelModel::where('id', '<>', $id)->take(4)->orderBy(DB::raw('RAND()'))->get();
			$get_viewvideo = VideoModel::where('string_Id', '=', $string_id)->where('channel_Id', '=', $id)->first();
			if ($get_viewvideo != NULL) {
				$getvideo = VideoModel::where('channel_Id', '=', $id)->where('string_Id', '<>', $string_id)->Orderby('total_view', 'desc')->paginate(perPage());
				return view('channel.channelvideo')->with('addvideo', $get_viewvideo)
								->with('channelvideo', $getvideo)
								->with('related', $get_related_channel)
								->with('channel', $get_channel)
								->with('categories', $categories)
								->with('totalvideo', $this->get_channel_total_video($id))
								->with('subscriber', $this->get_total_subscriber_channel($id))
								->with('check_subscriber', $this->get_subscriber_show($id));
			}
		}
		return redirect(getLang() . 'channel.html');
	}

	public function get_user_regist_channel(Request $get) {
		if ($get) {
			if (\Session::has('User')) {
				$user = \Session::get('User');
				$channel_name = $get->channel_name;
				$channel_description = $get->channel_description;
				$token = $get->_token;
				$check_token = ChannelModel::where('user_id', '=', $user->user_id)->first();
				if ($check_token == NULL) {
					$add = new ChannelModel();
					$add->title_name = $channel_name;
					$add->post_name = str_slug($channel_name);
					$add->user_id = $user->user_id;
					$add->description = $channel_description;
					$add->status = 3;
					if ($add->save()) {
						return 1;
					}
				}
				return 0;
			}
			return 2;
		}
	}

}
