<?php

namespace App\Http\Controllers\admincp;

use App\Services\Modules\Modules;
use App\Models\VideoModel;
use App\Models\ChannelModel;
use App\Models\PornStarModel;
use App\Models\CategoriesModel;
use App\Models\VideoCommentModel;
use App\Models\ActivityLogModel;
use App\Models\CountReportModel;
use App\Models\VideoTextAdsModel;
use App\Models\MSGPrivateModel;
use App\Models\ConversionModel;
use App\Models\StandardAdsModel;
use App\Models\VideoAdsModel;
use App\Models\VideoCatModel;
use App\Models\OptionModel;
use App\Models\WatermarkModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use App\Helper\AppHelper;
use DB;
use DateTime;
use Exception;
use Log;
use App\Services\TimeService;

//ffmpeg  -i /var/www/html/videos/2016-04-28/1722568934.mp4  -i /var/www/html/public/upload/watermark/logo-roy.png -filter_complex overlay -y  /var/www/html/videos/2016-04-28/690145653.mp4
// ffmpeg  -i /var/www/html/videos/2016-04-28/1722568934.mp4 -vf "movie=0:png: /var/www/html/public/upload/watermark/logo-roy.png [logo]; [in][logo] overlay=10:10:1" -y  /var/www/html/videos/2016-04-28/690145653.mp4

class VideoController extends Controller {

	public function get_video() {
		return view('admincp.video.index');
	}

	public function post_list_video(Request $post) {
		$start = $post->start;
		$length = $post->length;
		$col = $post->columns;
		$order = $post['order'][0];
		$orderby = $col[$order['column']]['data'];
		$orderby = $orderby==2?"title_name":($orderby==4?"title_channel":($orderby==5?"title_porn":($orderby==6?"upTime":$orderby)));
		$sortasc = $order['dir'];
		$criteria = "1=1";
		if($col[2]['search']['value']!="") {
			$keyword = $col[2]['search']['value'];
			$criteria = "table_video.title_name LIKE '%".$keyword."%'";
		} elseif($col[4]['search']['value']!="") {
			$keyword = $col[4]['search']['value'];
			$criteria = "table_channel.title_name LIKE '%".$keyword."%'";
		} elseif($col[5]['search']['value']!="") {
			$keyword = $col[5]['search']['value'];
			$criteria = "table_pornstar.title_name LIKE '%".$keyword."%'";
		}
		$recordsTotal = VideoModel::count();
		$recordsFiltered = VideoModel::select('video.id')
						->leftJoin('channel', 'channel.id', '=', 'video.channel_Id')
						->leftJoin('pornstar', 'pornstar.id', '=', 'video.pornstar_Id')
						->whereRaw($criteria)
						->count();
		$get_list = VideoModel::select('video.id', 'video.string_Id', 'video.title_name', 'video.categories_Id', 'video.upTime', 'video.status', 'video.featured',
						DB::raw('table_channel.title_name as title_channel'),
						DB::raw('table_pornstar.title_name as title_porn'))
						->leftJoin('channel', 'channel.id', '=', 'video.channel_Id')
						->leftJoin('pornstar', 'pornstar.id', '=', 'video.pornstar_Id')
						->whereRaw($criteria)
						->orderBy($orderby, $sortasc)
						->limit($length)->offset($start)
						->get();

		$data = $get_list->toArray();

		$items = [];
		foreach ($data as $key => $value) {
			foreach ($value as $k => $v) {
				if ($k == 'categories_Id') {
					$items[$key]['categories'] = get_categories_list($v);
				} else if ($k == 'upTime') {
					$items[$key]['upTime'] = date('Y-m-d h:i:s', $v);
				} else {
					$items[$key][$k] = $v;
				}
			}
		}

		$result = array(
			'data' => $items,
			'draw' => $post['draw'],
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered
		);

		return \Response::json($result);
	}

	public function postListVideo(Request $request) {
		$limit = $request->input('limit');
		$offset = $request->input('offset');
		$sort = $request->input('sort');
		$order = $request->input('order');

		$addvideo = VideoModel::select('video.*', 'channel.title_name as title_channel', 'pornstar.title_name as title_porn')
						->leftJoin('channel', 'channel.id', '=', 'video.channel_Id')
						->leftJoin('pornstar', 'pornstar.id', '=', 'video.pornstar_Id')
						->orderBy('video.' . $sort, $order)
						->skip($offset)
						->take($limit)
						->get();
		$data = $addvideo->toArray();
		$total = VideoModel::select('video.*', 'channel.title_name as title_channel', 'pornstar.title_name as title_porn')
						->leftJoin('channel', 'channel.id', '=', 'video.channel_Id')
						->leftJoin('pornstar', 'pornstar.id', '=', 'video.pornstar_Id')
						->count();
		$result = array('total' => $total, 'rows' => $data);
		return $result;
	}

	public function get_addvideo() {
		$categories = CategoriesModel::where('status', '=', CategoriesModel::ACTIVE)
						->orderBy('title_name', 'ASC')
						->get();
		$channel = ChannelModel::where('status', '=', ChannelModel::ACTIVE)
						->orderBy('created_at', 'ASC')
						->get();
		$pornstar = PornStarModel::where('status', '=', PornStarModel::ACTIVE)
						->orderBy('created_at', 'ASC')
						->get();

		if ($channel && $categories && $pornstar) {
			return view('admincp.video.upload')->with('categories', $categories)
											->with('channel', $channel)
											->with('pornstar', $pornstar);
		}
	}

	public function getUploadWatermark() {
		$user = \Session::get('logined');
		$getWatermark = WatermarkModel::where('userId', '=', $user->id)->first();
		if (!empty($getWatermark)) {
			return view('admincp.watermark.upload')->with('watermark', $getWatermark);
		}
		return view('admincp.watermark.upload');
	}

	public function uploadWatermark(Request $get) {
		$file = array('images' => Input::file('watermark'));
		if (Input::file('watermark')->isValid()) {
			$destinationPath = public_path() . '/upload/watermark/'; // upload path
			$fileName = Input::file('watermark')->getClientOriginalName(); // renameing image

			$user = \Session::get('logined');
			$getWatermark = WatermarkModel::where('userId', '=', $user->id)->first();
			if (!empty($getWatermark)) {
				$update = WatermarkModel::find($getWatermark->id);
				$update->path = 'upload/watermark/' . $fileName;
				$update->fullPath = $destinationPath . $fileName;
				$update->position = $get->position;
				$update->save();
			} else {
				$addnew = new WatermarkModel();
				$addnew->path = 'upload/watermark/' . $fileName;
				$addnew->fullPath = $destinationPath . $fileName;
				$addnew->position = $get->position;
				$addnew->userId = $user->id;
				$addnew->save();
			}
			$upload = Input::file('watermark')->move($destinationPath, $fileName); // uploading file to given path
		}
	}

	public function post_addvideo(Request $request) {
		$rules = [
			'title_name' => 'required|min:5',
			'fileupload' => 'required'
		];
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}

		$addvideo = new VideoModel();
		//$conversion_config = ConversionModel::get_config();

		$date = date('Y-m-d');
		$folder = base_path() . "/videos/" . $date . "";
		if (!is_dir($folder)) {
			$folder = mkdir(base_path() . "/videos/" . $date, 0777, true);
			$upload_folder = base_path() . "/videos/" . $date . "";
		} else {
			$upload_folder = base_path() . "/videos/" . $date . "";
		}

		if ($request->post_result_cat != NULL) {
			$tostring = implode(',', $request->post_result_cat);
		}

		$string = $request->string_id;
		$addvideo->string_Id          = $string;
		$addvideo->upTime             = time();
		$addvideo->title_name         = $request->title_name;
		$addvideo->post_name          = str_slug($request->title_name, "-");
		$addvideo->categories_Id      = isset($tostring) ? $tostring : '';
		$addvideo->cat_id             = isset($tostring) ? cat_form_array($tostring) : '';
		$addvideo->channel_Id         = $request->channel_Id;
		$addvideo->pornstar_Id        = $request->pornstar_Id;
		$addvideo->description        = $request->description;
		$addvideo->allowedTypes       = $request->allowedTypes;
		$addvideo->form_name          = $request->form_name;
		$addvideo->subscriptionTypeId = $request->subscriptionTypeId;
		$addvideo->status             = $request->status;
		$addvideo->comment_status     = $request->comment_status;
		$addvideo->rating             = $request->rating;
		$addvideo->tag                = $request->tag;
		$addvideo->website            = "upload";
		$addvideo->featured           = $request->featured;
		$addvideo->buy_this           = isset($request->buy_this) ? 1 : 0;
		$addvideo->status             = VideoModel::CONVERT_STATUS;
		if ($request->fileupload != NULL) {
			$file_info = $request->fileupload;
			$extend = explode(".", $file_info);
			$get_extend = strtolower(end($extend));
			$addvideo->extension = $get_extend;
		}

		if ($addvideo->save()) {
			if (isset($tostring)) {
				$cat_post = cat_array($tostring);

				for ($i = 0; $i < count($cat_post); $i++) {
					$videocat = new VideoCatModel();
					$videocat->video_id = $request->string_id;
					$videocat->cat_id = $cat_post[$i];
					$videocat->save();
				}
			}

			return redirect('admincp/video')->with('msg', 'Added video successfully!');
		}
	}

	public function get_video_info($input) {
		$conversion_config = ConversionModel::get_config();
		$cmd = shell_exec("" . $conversion_config->mediainfo_path . " " . $input . "");
		var_dump(json_encode($cmd));
	}

	public function get_video_thumb_gif($input_video, $string) {

		$conversion_config = ConversionModel::get_config();
		$date = date('Y-m-d');
		$folder = base_path() . "/videos/thumb/" . $date . "";
		if (!is_dir($folder)) {
			$folder = mkdir(base_path() . "/videos/thumb/" . $date, 0777, true);
			$upload_folder_thum = base_path() . "/videos/thumb/" . $date . "/";
		} else {
			$upload_folder_thum = base_path() . "/videos/thumb/" . $date . "/";
		}
		$path_out_thumb = "" . $upload_folder_thum . "/" . $string . "_";
		shell_exec("" . $conversion_config->ffmpeg_path . " -i " . $input_video . " -vf fps=1/60 " . $path_out_thumb . "%d.jpg");
	}

	public function get_deletevideo($string_Id) {
		$get = VideoModel::where('string_Id', '=', $string_Id)->first();
		if ($get != NULL) {
			$extension = $get->extension;
			$createdAt = $get->created_at;
			$getvideo = VideoModel::where('string_Id', '=', $string_Id)->delete();
			if ($getvideo) {
				$deleteCat = VideoCatModel::where('video_id', '=', $string_Id);
				if ($deleteCat->count() > 0) {
					$deleteCat->delete();
				}

				//Get Path of video
				$get_path_date = date_format(new DateTime($createdAt), 'Y-m-d');

				$videoInput    = base_path() . DIRECTORY_SEPARATOR . 'videos'. DIRECTORY_SEPARATOR .$string_Id.'.'.$extension;
				$videoOutput   = base_path() . DIRECTORY_SEPARATOR . 'videos'. DIRECTORY_SEPARATOR .'_'.$get_path_date.'-'.$string_Id.'.mp4';
				$videoSD       = base_path() . DIRECTORY_SEPARATOR . 'videos'. DIRECTORY_SEPARATOR .'_'.$get_path_date.'-'.$string_Id.'-SD.mp4';
				$thumbnailPath = base_path() . DIRECTORY_SEPARATOR . 'videos'. DIRECTORY_SEPARATOR .'_'.$get_path_date.'-'.$string_Id.'.jpg';
				$previewPath   = base_path() . DIRECTORY_SEPARATOR . 'videos'. DIRECTORY_SEPARATOR .'_'.$get_path_date.'-'.$string_Id.'-preview.jpg';

				@unlink ( $videoInput );
				@unlink ( $videoOutput );
				@unlink ( $videoSD );
				@unlink ( $thumbnailPath );
				@unlink ( $previewPath );

				return redirect('admincp/video')->with('msg', 'Delete video successfully!');
			}
			return redirect('admincp/video')->with('msgerror', 'Request not found !');
		}
		return redirect('admincp/video')->with('msgerror', 'Request not found !');
	}

	public function get_editvideo($string_Id) {
		$getvideo = VideoModel::where('string_Id', '=', $string_Id)
						->first();
		$categories = CategoriesModel::where('status', '=', CategoriesModel::ACTIVE)
						->orderBy('created_at', 'ASC')
						->get();
		$channel = ChannelModel::where('status', '=', ChannelModel::ACTIVE)
						->orderBy('created_at', 'ASC')
						->get();
		$pornstar = PornStarModel::where('status', '=', PornStarModel::ACTIVE)
						->orderBy('created_at', 'ASC')
						->get();

		if ($getvideo && $categories && $channel) {
			return view('admincp.video.edit')->with('getvideo', $getvideo)
											->with('categories', $categories)
											->with('channel', $channel)
											->with('pornstar', $pornstar)
											->with('title_pornstar', 'Manage Existing Videos');
		}
	}

	public function post_editvideo(Request $get, $string_Id) {
		$conversion_config = ConversionModel::get_config();
		if ($get->post_result_cat != NULL) {
			$tostring = implode(',', $get->post_result_cat);
		}
		$updatevideo = VideoModel::where('string_Id', '=', $string_Id)->update(array(
				'title_name' => $get->title_name,
				'post_name' => str_slug($get->title_name, "-"),
				'description' => $get->description,
				'tag' => $get->tag,
				'comment_status' => $get->comment_status,
				'allowedTypes' => $get->allowedTypes,
				'form_name' => $get->form_name,
				'subscriptionTypeId' => $get->subscriptionTypeId,
				'status' => $get->status,
				'categories_Id' => isset($tostring) ? $tostring : '',
				'cat_id' => isset($tostring) ? cat_form_array($tostring) : '',
				'channel_Id' => $get->channel_Id,
				'pornstar_Id' => $get->pornstar_Id,
				'porn_star' => $get->porn_star,
				'rating' => $get->rating,
				'featured' => $get->featured,
				'buy_this' => isset($get->buy_this) ? 1 : 0
		));
		$check_video_cat = VideoCatModel::where('video_id', '=', $string_Id)->count();
		if ($check_video_cat > 0) {
			$delete_video_cal = VideoCatModel::where('video_id', '=', $string_Id)->delete();
			if (isset($tostring)) {
				$cat_post = cat_array($tostring);
				for ($i = 0; $i < count($cat_post); $i++) {
					$videocat = new VideoCatModel();
					$videocat->video_id = $string_Id;
					$videocat->cat_id = $cat_post[$i];
					$videocat->save();
				}
			}
		} else {

			if (isset($tostring)) {
				$cat_post = cat_array($tostring);
				for ($i = 0; $i < count($cat_post); $i++) {
					$videocat = new VideoCatModel();
					$videocat->video_id = $string_Id;
					$videocat->cat_id = $cat_post[$i];
					$videocat->save();
				}
			}
		}


		if ($updatevideo) {
			return redirect('admincp/video')->with('msg', 'Update video successfully!');
		}
		return redirect('admincp/edit-video')->with('msgerror', 'Update video not complete!');
	}

	public function sec2hms($sec, $padHours = false) {
		$hms = "";
		$hours = intval(intval($sec) / 3600);
		if ($hours > 0) {
			$hms .= ($padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT) . ':' : $hours . ':';
		}
		$minutes = intval(($sec / 60) % 60);
		$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT) . ':';
		$seconds = intval($sec % 60);
		$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
		return $hms;
	}

	public function get_comment() {
		$get_comment = VideoCommentModel::select('video_comment.*', 'members.username', 'video.string_Id', 'video.poster', 'video.title_name')
						->join('video', 'video.string_Id', '=', 'video_comment.video_Id')
						->join('members', 'members.user_id', '=', 'video_comment.member_Id')
						->get();
		if (count($get_comment) > 0) {
			return view('admincp.comment.videocomment')->with('comment', $get_comment);
		} else {
			return view('admincp.comment.videocomment')->with('msg', ' No comment for all video');
		}
	}

	public function get_delete_comment($id) {
		VideoCommentModel::find($id)->delete();
		return redirect('admincp/video-comment')->with('msg-success', 'Delete comment successfully !');
	}

	public function get_edit_comment($id) {
		$edit = VideoCommentModel::select('video_comment.*', 'members.username', 'video.string_Id', 'video.poster', 'video.title_name')
						->join('video', 'video.string_Id', '=', 'video_comment.video_Id')
						->join('members', 'members.user_id', '=', 'video_comment.member_Id')
						->where('video_comment.id', '=', $id)
						->first();
		return view('admincp.comment.editcomment')->with('edit', $edit)->with('porn_name', 'Manage Video Comments ');
	}

	public function post_edit_comment($id) {
		if ($id != NULL) {
			$data = array(
					"post_comment" => $_POST['post_comment'],
			);
			$getupdate = VideoCommentModel::where('id', '=', $id)->update($data);
			if ($getupdate) {
				return redirect('admincp/video-comment')->with('msg-success', 'Update successfuly !');
			}
			return redirect('edit-video-comments/' . $id . '')->with('msg-error', 'Update not successfuly.Please try again !');
		}
	}

	public function get_report($listid) {
		if ($listid != NULL) {
			$get_count = CountReportModel::where('report_status', '=', 1)->first();
			$countreport = CountReportModel::where('report_status', '=', 1)->update(array('count_report' => $get_count->count_report + 1));
			$get_title = VideoModel::where('string_Id', '=', $listid)->first();
			$getcomment = VideoCommentModel::select('video_comment.*', 'members.firstname', 'members.lastname', 'video.title_name')
							->where('video_comment.video_Id', '=', $listid)
							->join('members', 'members.user_id', '=', 'video_comment.member_Id')
							->join('video', 'video.string_Id', '=', 'video_comment.video_Id')
							->get();
			$stt = 2;
			$i = 1;
			Excel::create('Report Comment ' . $get_title->title_name . ' ' . date('D-M-Y') . '', function($excel)use ($getcomment, $i, $stt) {
				$excel->sheet('sheet1', function($sheet)use($getcomment, $i, $stt) {
					$sheet->cells('A1:G1', function($cells) {
						$cells->setBackground('#ee577c');
						$cells->setFontColor('#ffffff');
					});
					$sheet->SetCellValue("A1", "#");
					$sheet->SetCellValue("B1", "Video ID");
					$sheet->SetCellValue("C1", "Video Title");
					$sheet->SetCellValue("D1", "First Name");
					$sheet->SetCellValue("E1", "Last Name");
					$sheet->SetCellValue("F1", "Comment");
					$sheet->SetCellValue("G1", "Created At");
					foreach ($getcomment as $result) {
						$sheet->row($stt++, array($i++, $result->video_Id, $result->title_name, $result->firstname, $result->lastname, $result->post_comment, $result->created_at));
					}
				});
			})->download('xls');
		}
	}

	public function get_standard_ads() {
		return view('admincp.ads.standard_list');
	}

	public function get_add_standard_ads() {
		return view('admincp.ads.add_standard_ads');
	}

	public function post_add_standard_ads(Request $get) {
		if ($get->type === 'upload') {
			$rules = [
					'ads_title' => 'required|min:5',
					'ads_content' => 'required|image|mimes:jpeg,png,jpg,gif',
					'return_url' => 'required|url|',
			];
			$validator = Validator::make($get->all(), $rules);
			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}
		}
		if ($get->type === 'script_code') {
			$rules = [
					'ads_title' => 'required|min:5',
					'script_code' => 'required|',
			];
			$validator = Validator::make($get->all(), $rules);
			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput();
			}
		}

		$string = mt_rand();
		$addstandard = new StandardAdsModel();
		$addstandard->ads_title = $get->ads_title;
		$addstandard->position = $get->position;
		$addstandard->return_url = $get->return_url;
		$addstandard->status = $get->status;
		$addstandard->string_id = $string;
		$addstandard->type = $get->type;
		$addstandard->script_code = $get->script_code;

		$file = Input::file('ads_content');
		if ($file) {

			$extension = $file->getClientOriginalExtension();
			$destinationPath = public_path() . "/upload/ads/";
			$filename = "Standard_" . $string . "." . $extension;
			$file->move($destinationPath, $filename);
			$addstandard->ads_content = asset('public/upload/ads/' . $filename);
			$addstandard->ads_type = $extension;
		}

		if ($addstandard->save()) {
			return redirect('admincp/ads-standard')->with('msg-success', 'Upload successfuly !');
		}
		return redirect('admincp/add-standard-ads')->with('msg-error', 'Upload not success !');
	}

	public function get_edit_standard_ads($id) {
		if ($id != NULL or $id != " ") {
			$checkid = StandardAdsModel::find($id);
			if ($checkid != NULL) {
				return view('admincp.ads.edit_standardads')->with('editstandard', $checkid)->with('title_pornstar', 'Manage Standard Ads');
			}
			return redirect('admincp/ads-standard')->with('msg-error', 'Ads  not found !');
		}
		return redirect('admincp/ads-standard')->with('msg-error', 'Ads  not found !');
	}

	public function post_edit_standard_ads(Request $get, $id) {
		if ($id != NULL or $id != " ") {
			$checkid = StandardAdsModel::where('id', '=', $id);
			if ($checkid->first() != NULL) {
				$string = $get->string_id;
				$cl_version = $get->cl_version;
				$data = array(
						'ads_title' => $get->ads_title,
						'position' => $get->position,
						'return_url' => $get->return_url,
						'status' => $get->status,
						'string_id' => $string,
						'type' => $get->type,
						'script_code' => $get->script_code
				);
				$updateAds = $checkid->update($data);
				$file = Input::file('ads_content');
				if ($file) {

					$extension = $file->getClientOriginalExtension();

					$notAllowed = array("exe", "php", "asp", "pl", "bat", "js", "jsp", "sh", "doc", "docx", "xls", "xlsx");

					$destinationPath = public_path() . "/upload/ads/";

					$filename = "Standard_" . $string . "." . $extension;

					if (!in_array($extension, $notAllowed)) {
						$file->move($destinationPath, $filename);
						$updatestandard = StandardAdsModel::where('id', '=', $id)->update([
								'ads_content' => asset('public/upload/ads/' . $filename),
								'ads_type' => $extension
						]);
					}
				}
				if ($updateAds) {
					return redirect('admincp/ads-standard')->with('msg-success', 'Update Ads successfuly !');
				}
			}
			return redirect('admincp/ads-standard')->with('msg-error', 'Ads  not found !');
		}
		return redirect('admincp/ads-standard')->with('msg-error', 'Ads  not found !');
	}

	public function del_standard_ads($id) {
		if ($id != NULL or $id != "") {
			$checkAds = StandardAdsModel::find($id);
			if (!empty($checkAds)) {
				if (!empty($checkAds->ads_content)) {
					if (File::exists(public_path() . "/upload/ads/" . $checkAds->ads_content)) {
						unlink(public_path() . "/upload/ads/" . $checkAds->ads_content);
					}
				}
				if ($checkAds->delete()) {
					return redirect('admincp/ads-standard')->with('msg-success', 'Delete Ads successfuly');
				}
				return redirect('admincp/ads-standard')->with('msg-error', 'Delete Ads not success');
			}
			return redirect('admincp/ads-standard')->with('msg-error', 'Ads not found !');
		}
		return redirect('admincp/ads-standard')->with('msg-error', 'Ads not found !');
	}

	public function post_list_standard_ads(Request $post) {
		$start = $post->start;
		$length = $post->length;
		$col = $post->columns;
		$order = $post['order'][0];
		$orderby = $col[$order['column']]['data'];
		$orderby = $orderby==1?"ads_title":$orderby;
		$sortasc = $order['dir'];
		$criteria = "1=1";
		if($col[1]['search']['value']!="") {
			$keyword = $col[1]['search']['value'];
			$criteria = "table_standard_ads.ads_title LIKE '%".$keyword."%'";
		}
		$recordsTotal = StandardAdsModel::count();
		$recordsFiltered = StandardAdsModel::select('id')
						->whereRaw($criteria)
						->count();
		$get_list = StandardAdsModel::select('standard_ads.*')
						->whereRaw($criteria)
						->orderBy($orderby, $sortasc)
						->limit($length)->offset($start)
						->get();

		$result = array(
			'data' => $get_list,
			'draw' => $post['draw'],
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered
		);

		return \Response::json($result);
	}

	public function delete_all_standard_ads($string_id) {
		$get = StandardAdsModel::whereRaw('id IN(' . $string_id . ')')->get();
		$deletedAds = true;
		if ($get) {
			foreach ($get as $key => $value) {
				if (!empty($value->ads_content)) {
					if (File::exists(public_path() . "/upload/ads/" . $value->ads_content)) {
						unlink(public_path() . "/upload/ads/" . $value->ads_content);
					}
				}
				$checker = $value->delete();
				if (!$checker)
					$deletedAds = false;
			}
			if ($deletedAds) {
				return redirect('admincp/ads-standard')->with('msg', 'Deleted standard ads successfully!');
			} else {
				return redirect('admincp/ads-standard')->with('msgerror', 'Deleted standard ads unsuccessfully!');
			}
		}
		return redirect('admincp/ads-standard')->with('msgerror', 'Request not found !');
	}

	public function get_admin_reply($id) {
		$comment = VideoCommentModel::select('video_comment.*', 'members.username', 'video.string_Id', 'video.poster', 'video.title_name')
						->join('video', 'video.string_Id', '=', 'video_comment.video_Id')
						->join('members', 'members.user_id', '=', 'video_comment.member_Id')
						->where('video_comment.id', '=', $id)
						->first();
		return view('admincp.comment.reply')->with('comment', $comment)->with('title_pornstar', 'Manage Video Comments ');
	}

	public function post_admin_reply($id, Request $req) {
		if ($id != NULL) {
			$comment = VideoCommentModel::select('video_comment.*', 'members.username')
							->join('members', 'members.user_id', '=', 'video_comment.member_Id')
							->where('video_comment.id', '=', $id)
							->first();
			if ($comment) {
				$timeService = new TimeService();

				$user = \Session::get('logined');
				$post_comment = $req->post_comment;
				$memberid = $user->user_id;
				$addcomment = new VideoCommentModel();
				$addcomment->video_Id = $comment->video_Id;
				$addcomment->member_Id = $memberid;
				$addcomment->comment_parent = $id;
				$addcomment->post_comment = $post_comment;
				$addcomment->created_at = $timeService->dateWithTimeZone(date('Y-m-d H:i:s'));

				if ($addcomment->save()) {
					return redirect('admincp/video-comment')->with('msg-success', 'Your reply successfuly !');
				}
				return redirect('admincp/admin-reply-comment')->with('msg-error', 'Your reply successfuly !');
			}
		}
		return redirect('admincp/video-comment')->with('msg-error', 'Can\'t found this comment !');
	}

	public function auto_upload_video() {
		try {
			$upload_folder = "".$_SERVER['DOCUMENT_ROOT']."/videos/";
			if(!is_dir($upload_folder)) {
				$folder = mkdir($upload_folder, 0777, true);
			}

			Log::info('Upload DOCUMENT_ROOT: ' . $_SERVER['DOCUMENT_ROOT']);

			if( isset($_FILES["myfile"]) ) {
				Log::info('Upload myfile:' . json_encode($_FILES["myfile"]));
				if(!$_FILES["myfile"]["error"]) {
					$string_id = $_POST['string_id'];

					if(!is_array($_FILES["myfile"]["name"])) {
						$file_info = $_FILES["myfile"]["type"];
						$get_extend = '';
						if (in_array( $file_info,['video/flv', 'video/x-flv', 'application/octet-stream'])) {
							$get_extend = 'flv';
						} else {
							$extend = explode("/", $file_info);
							$get_extend = end($extend);
						}
						Log::info('Upload myfile type: ' . $file_info);
						$fileName = "".$string_id.".".$get_extend."";
						$upload_video =  move_uploaded_file($_FILES["myfile"]["tmp_name"], $upload_folder.$fileName);
						Log::info('Upload myfile: ' . $upload_video);
					} else {
						throw new Exception("Not exist myfile name!", 1);
					}
				} else {
					throw new Exception("Got error file!", 1);
				}
			} else {
				throw new Exception("Not exist myfile!", 1);
			}
		} catch (\Exception $e) {
			Log::error('Upload ERR: ' . $e->getMessage());
			return response()->json(["error" => $e->getMessage()]);
		}

		return response()->json(["status" => "ok"]);
	}

	public function del_message($id) {
		if ($id) {
			$checkid = MSGPrivateModel::find($id);
			if ($checkid != NULL) {
				if ($checkid->delete()) {
					return redirect('admincp/private-message')->with('msg', ' Message has been deleted successfully');
				}
			}
			return redirect('admincp/private-message')->with('msgerror', 'Message is not found !');
		}
	}

	public function getBulkVideo() {
		$categories = CategoriesModel::where('status', '=', CategoriesModel::ACTIVE)
						->orderBy('created_at', 'ASC')
						->get();
		$channel = ChannelModel::where('status', '=', ChannelModel::ACTIVE)
						->orderBy('created_at', 'ASC')
						->get();
		$pornstar = PornStarModel::where('status', '=', PornStarModel::ACTIVE)
						->orderBy('created_at', 'ASC')
						->get();


		return view('admincp.video.bulk_upload', compact('categories', 'channel', 'pornstar'));
	}

	public function postBulkVideo() { }

	public function postBulkAutoUpload(Request $get) {
		try {
			$upload_folder = "".$_SERVER['DOCUMENT_ROOT']."/videos/";
			if(!is_dir($upload_folder)) {
				$folder = mkdir($upload_folder, 0777, true);
			}

			Log::info('Upload DOCUMENT_ROOT: ' . $_SERVER['DOCUMENT_ROOT']);

			if( isset($_FILES["myfile"]) ) {
				Log::info('Upload myfile:' . json_encode($_FILES["myfile"]));
				if(!$_FILES["myfile"]["error"]) {
					$extension = Input::file('myfile')->getClientOriginalExtension(); // getting image extension

					$stringName = mt_rand();
					$fileName = $stringName . '.' . $extension; // renameing image

					$addvideo             = new VideoModel();
					$addvideo->upTime     = time();
					$addvideo->string_Id  = $stringName;
					$addvideo->website    = 'upload';
					$addvideo->title_name = $stringName . "#Untitle";
					$addvideo->post_name  = str_slug($stringName . "#Untitle", "-");
					$addvideo->status     = VideoModel::CONVERT_STATUS;
					$addvideo->extension  = $extension;
					if ($addvideo->save()) {
						// Input::file('myfile')->move($upload_folder, $fileName); // uploading file to given path
						$upload_video =  move_uploaded_file($_FILES["myfile"]["tmp_name"], $upload_folder.$fileName);
						Log::info('Upload myfile: ' . $upload_video);
					} else {
						throw new Exception("Cannot save the video file!", 1);
					}
				} else {
					throw new Exception("Got error file!", 1);
				}
			} else {
				throw new Exception("Not exist myfile!", 1);
			}
		} catch (\Exception $e) {
			Log::error('Upload ERR: ' . $e->getMessage());
			return response()->json(["error" => $e->getMessage()]);
		}

		return response()->json(["status" => "ok"]);
	}

	public function get_duplicates_video() {
		return view('admincp.video.duplicates');
	}

	public function get_duplicates_list_video(Request $request) {
		$start = $request->start;
		$length = $request->length;
		$col = $request->columns;
		$order = $request['order'][0];
		$orderby = $col[$order['column']]['data'];
		$orderby = "title_name";
		$sortasc = $order['dir'];

		$recordsTotal = VideoModel::select('video.id', 'video.string_Id', 'video.title_name', DB::raw('COUNT(*) as total_duplicaties'))
						->groupBy('post_name','duration', 'video_url')
						->orderBy($orderby, $sortasc)
						->havingRaw('COUNT(*) > 1')->get()->toArray();

		$recordsFiltered = VideoModel::select('video.id')
						->groupBy('post_name','duration', 'video_url')
						->havingRaw('COUNT(*) > 1')
						->count();

		$get_list = VideoModel::select('video.id', 'video.string_Id', 'video.title_name', DB::raw('COUNT(*) as total_duplicaties'))
						->groupBy('post_name','duration', 'video_url')
						->orderBy($orderby, $sortasc)
						->havingRaw('COUNT(*) > 1')
						->limit($length)->offset($start)
						->get();
		// dd($get_list->toArray());

		$result = array(
			'data' => $get_list,
			'draw' => $request['draw'],
			'recordsTotal' => count($recordsTotal),
			'recordsFiltered' => count($recordsTotal)
		);

		return \Response::json($result);
	}

	public function get_duplicates_video_detail($id, Request $request) {
		return view('admincp.video.duplicates_detail')->with('id', $id);
	}

	public function get_duplicates_list_video_detail($id, Request $request) {
		$limit = $request->input('limit');
		$offset = $request->input('offset');
		$sort = $request->input('sort');
		$order = $request->input('order');
		$addvideo = VideoModel::select('video.*', 'channel.title_name as title_channel', 'pornstar.title_name as title_porn')
						->leftJoin('channel', 'channel.id', '=', 'video.channel_Id')
						->leftJoin('pornstar', 'pornstar.id', '=', 'video.pornstar_Id')
						->where('video.video_url', 'LIKE', \DB::raw('(select video_url from table_video where string_Id=' . $id . ' LIMIT 1) '))
						->orderBy('video.' . $sort, $order)
						->skip($offset)
						->take($limit)
						->get();
		$data = $addvideo->toArray();
		$total = VideoModel::where('video_url', '=', \DB::raw('(select video_url from table_video where string_Id=' . $id . ")"))->count();
		$result = array('total' => $total, 'rows' => $data);
		return $result;
	}

	public function get_delete_duplicates_video($string_Id) {
		$get = VideoModel::whereRaw('string_Id IN(' . $string_Id . ')')->count();
		if ($get) {
			$getvideo = VideoModel::whereRaw('string_Id IN(' . $string_Id . ')')->delete();
			if ($getvideo) {
				$deleteCat = VideoCatModel::where('video_id', '=', $string_Id);
				if ($deleteCat->count() > 0) {
					$deleteCat->delete();
				}
				return redirect('admincp/duplicates-video')->with('msg', 'Deleted video successfully!');
			} else {
				return redirect('admincp/duplicates-video')->with('msgerror', 'Request not found!');
			}
		} else {
			return redirect('admincp/duplicates-video')->with('msgerror', 'Request not found!');
		}
	}

	public function get_delete_all_duplicates_video() {
		$addvideo = \DB::select('
				SELECT `table_video`.*, COUNT(*) as total_duplicaties FROM `table_video` LEFT JOIN `table_channel` ON `table_channel`.`id` = `table_video`.`channel_Id` LEFT JOIN `table_pornstar` ON `table_pornstar`.`id` = `table_video`.`pornstar_Id`  GROUP BY `table_video`.`post_name`,`table_video`.`duration` HAVING COUNT(*) > 1 ORDER BY `table_video`.`created_at` DESC ')
		;
		if (count($addvideo) > 0) {
			foreach ($addvideo as $key => $data) {
				$getvideo = VideoModel::where('string_Id', '!=', $data->string_Id)->where('post_name', '=', $data->post_name)->where('duration', '=', $data->duration)->delete();
				$deleteCat = VideoCatModel::where('video_id', '=', $data->string_Id);
				if ($deleteCat->count() > 0) {
					$deleteCat->delete();
				}
			}
		}
		return redirect('admincp/duplicates-video')->with('msg', 'Deleted video successfully!');
	}

	public function get_delete_video_csv() {
		$tubeWeb = VideoModel::$tubeCopWeb;
		return view('admincp.video.delete_video', compact('tubeWeb'));
	}

	public function auto_upload_csv(Request $req) {
		try {
			$upload_folder = public_path() . "/upload/csv/";
			if (!is_dir($upload_folder)) {
				$folder = mkdir($upload_folder, 0777, true);
			}

			$file = $req->file('myfile');
			if (isset($file) && $file->getClientOriginalExtension() == "csv" && !$_FILES["myfile"]["error"]) {
				$string_id = $req->input('string_id');
				$fileName = "" . $string_id . ".csv";
				$upload_video = move_uploaded_file($_FILES["myfile"]["tmp_name"], $upload_folder . $fileName);
			} else {
				throw new Exception("Got error file!", 1);
			}
		} catch (\Exception $e) {
			Log::error('Upload ERR: ' . $e->getMessage());
			return response()->json(["error" => $e->getMessage()]);
		}

		return response()->json(["status" => "ok"]);
	}

	public function post_delete_video_csv(Request $req) {
		$file = $req->input('file');
		$tubeWeb = VideoModel::$tubeCopWeb;

		if ($file) {
			$folder = public_path() . "/upload/csv/" . $req->input('string_id') . '.csv';

			$data_array = array();
			if (file_exists($folder) == true) {
				$csv_file = fopen($folder, "r");
				while (!feof($csv_file)) {
					if(in_array($req->website, $tubeWeb)) {
						$json = fgetcsv($csv_file,0, '|');
					} else {
						$json = fgetcsv($csv_file);
					}
					array_push($data_array, $json);
				}
				fclose($csv_file);
			} else {
				return back()->with('msg', '<div class="alert alert-danger">Folder is not exist!</div>');
			}

			$urls = [];
			if ($req->website == "www.pornhub.com") {
				foreach ($data_array as $key => $result) {

					$result[0] = str_replace(array('"|"', '"|', '|"'), '|', $result[0]);

					$toArray = explode('|', $result[0]);

					$itemsCount = count($result);

					$lastItem = str_replace(array('"|"', '"|', '|"'), '|', $result[$itemsCount - 1]);

					$detail = explode('|', $lastItem);

					if (isset($toArray) && count($toArray) >= 6 ) {
						$urls[] = $toArray[1];
					}
				}
			} else if ($req->website == "www.redtube.com") {
				unset($data_array[0]);
				foreach ($data_array as $key => $result) {

					$result[0] = str_replace(array('"|"', '"|', '|"'), '|', $result[0]);

					$toArray = explode('|', $result[0]);

					if (isset($toArray) && count($toArray) == 11 ) {
						$urls[] = $toArray[3];
					}
					else if (isset($toArray) && count($toArray) == 10) { // new format
						$urls[] = $toArray[2];
					}
				}
			} else if ($req->website == "www.tube8.com") {
				foreach ($data_array as $key => $result) {

					$toArray = explode('|', $result[0]);

					if (isset($toArray) && count($toArray) == 11) {
						$urls[] = $toArray[1];
					}
				}
			} else if ($req->website == "www.youporn.com") {
				foreach ($data_array as $key => $result) {

					$toArray = explode('|', $result[0]);

					if (isset($toArray) && count($toArray) >= 11) {
						$urls[] = 'https://'.$req->website.$toArray[1];
					}
				}
			} else if ($req->website == "www.xtube.com") {
				foreach ($data_array as $key => $result) {

					$toArray = explode('|', $result[0]);

					if (isset($toArray) && count($toArray) >= 9) {
						$urls[] = $toArray[1];
					}
				}
			} else if(in_array($req->website, $tubeWeb)) {
				foreach ($data_array as $key => $result) {
					if (isset($result[7])) {
						$urls[] = $result[7];
					}
				}
			}

			if (!empty($urls)) {
				$check = VideoModel::whereIn('video_url', $urls)->delete();
				if ($check)
					return back()->with('msg', '<div class="alert alert-success">Deleted video successfully!</div>');
				else
					return back()->with('msg', '<div class="alert alert-danger">Delete video unsuccessfully!</div>');
			} else
				return back()->with('msg', '<div class="alert alert-danger">Please try another file & select appropreate website!</div>');
		} else {
			return back()->with('msg', '<div class="alert alert-danger">please choose file</div>');
		}
	}

	public function get_categories_list($cat_array) {
		$cat = explode(',', $cat_array);
		$cat_list = array();
		for ($i = 0; $i < count($cat); $i++) {
			$catID = explode("_", $cat[$i]);
			array_push($cat_list, $catID[0]);
		}
		$categories_name = CategoriesModel::select('title_name')->whereIn('id', $cat_list)->where('status', '=', '1')->get();
		$currentCat = array();
		if (count($categories_name) > 0) {
			foreach ($categories_name as $value) {
				array_push($currentCat, $value->title_name);
			}
			return implode(",", $currentCat);
		}
		return;
	}

	public function deleteAllVideo($string_id) {
		$get = VideoModel::whereRaw('string_Id IN(' . $string_id . ')')->count();
		if ($get) {
			$getvideo = VideoModel::whereRaw('string_Id IN(' . $string_id . ')')->delete();
			if ($getvideo) {
				$deleteCat = VideoCatModel::whereRaw('video_id IN(' . $string_id . ')');
				if ($deleteCat->count() > 0) {
					$deleteCat->delete();
				}
				return redirect('admincp/video')->with('msg', 'Deleted videos successfully!');
			}
			return redirect('admincp/video')->with('msgerror', 'Request not found!');
		}
		return redirect('admincp/video')->with('msgerror', 'Request not found!');
	}

}
