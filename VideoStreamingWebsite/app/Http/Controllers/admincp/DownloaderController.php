<?php

namespace App\Http\Controllers\admincp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\VideoModel;
use App\Models\CategoriesModel;
use App\Models\ChannelModel;
use App\Models\PornStarModel;
use Illuminate\Support\Facades\Cache;
use Input;
use Exception;
use DB;
use Log;

class DownloaderController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$video = VideoModel::select('video.*', 'channel.title_name as title_channel', 'categories.title_name as title_categories', 'pornstar.title_name as title_porn')
						->where('video.status', '=', '1')
						->where('video.dowloader', '=', '1')
						->leftJoin('channel', 'channel.id', '=', 'video.channel_Id')
						->leftJoin('categories', 'categories.id', '=', 'video.categories_Id')
						->leftJoin('pornstar', 'pornstar.id', '=', 'video.pornstar_Id')
						->orderBy('video.created_at', 'ASC')
						->get();
		if ($video) {
			return view('admincp.dowload.index')->with('video', $video);
		}
	}

	public function add_view() {
		$categories = CategoriesModel::where('status', '=', CategoriesModel::ACTIVE)->get();
		$channel = ChannelModel::where('status', '=', ChannelModel::ACTIVE)->get();
		$pornstar = PornStarModel::where('status', '=', PornStarModel::ACTIVE)->get();

		return view('admincp.dowload.add')->with('categories', $categories)->with('channel', $channel)->with('pornstar', $pornstar);
	}

	public function add(Request $data) {
		session(['addvideotype'=>'grab']);

		$rule = array(
			'link' => 'required',
		);
		$validator = Validator::make($data->all(), $rule);
		if ($validator->fails()) {
			return redirect('admincp/dowload-video-add-view')->withErrors($validator);
		} else {
			$website = $data->website;
			$url = $data->link;

			// $video = VideoModel::where(DB::raw("CAST(table_video.video_url AS CHAR)"), "=", "'" . $url . "'")->orWhere(DB::raw("CAST(table_video.video_src AS CHAR)"), "=", "'" . $url . "'")->first();
			// $video = VideoModel::where("video_url", $url)->orWhere("video_src", $url)->first();
			// dd($video);
			$error = NULL;
			// if (empty($video)) {
				if (strpos($url, $website)) {
					$result = $this->check_url_video($url);

					if(!empty($result) && !is_null($result['link']) && !is_null($result['video_url'])) {
						$string = mt_rand();
						if (isset($data->post_result_cat)) {
							$tostring = implode(',', $data->post_result_cat);
						}
						$model                   = new VideoModel();
						// $model->categories_Id = $data->category;
						$model->categories_Id    = isset($tostring) ? $tostring : '';
						$model->cat_id           = isset($tostring) ? cat_form_array($tostring) : '';
						$model->channel_Id       = $data->channel;
						$model->upTime           = time();
						$model->pornstar_Id      = $data->pornstar;
						$model->title_name       = $result['title'];
						$model->post_name        = str_slug($result['title']);
						$model->video_sd         = $result['link'];
						$model->isEmbed          = $result['isEmbed'];
						$model->embedCode        = $result['embedCode'];
						$model->video_src        = $result['linkHD'];
						$model->video_url        = $result['video_url'];
						$model->digitsSuffix     = $result['digitsSuffix'];
						$model->digitsPreffix    = $result['digitsPreffix'];
						$model->preview          = $result['preview'];
						$model->poster           = str_replace('\/', '/', $result['image']);
						$model->tag              = $result['tag'];
						$model->string_Id        = $string;

						if (!empty($result['duration']) && !is_null($result['duration'])) {
							if (strlen($result['duration']) != strlen((int) $result['duration'])) {
								$duration = convert_time($result['duration']);
							} else {
								$dura = (int) $result['duration'];
								$duration = $dura;
							}
						} else {
							$duration = 0;
						}
						$model->duration = $duration;
						$model->description = $result['description'];
						$model->status = $data->status;
						// if (strpos($result['image'], 'pornfun.com')) {
						//     $model->duration = $result['duration'];
						// }
						// if (strpos($result['image'], 'www.vporn.com')) {
						//     $model->duration = $result['duration'];
						// }
						$model->website = $website;
						$model->dowloader = 1;
						if($model->save()) {
							if (isset($tostring)) {
								$cat_post = cat_array($tostring);
								for ($i = 0; $i < count($cat_post); $i++) {
									$videocat = new \App\Models\VideoCatModel();
									$videocat->video_id = $string;
									$videocat->cat_id = $cat_post[$i];
									$videocat->save();
								}
							}
							$success = "Save video success";
						}
						return redirect('admincp/video')->with('success', $success);
					} else {
						$error = array('error' => "Import failed!Url is incorrect with website!");
					}
				} else {
					$error = array('error' => "Url is incorrect with website!");
				}
			// } else {
			// 	$error = array('error' => "Video is existing!");
			// }

			if (!is_null($error)) {
				return redirect('admincp/dowload-video-add-view')->withErrors($error);
			}
		}
	}

	public function check_url_video($url) {
		$get_url = parse_url($url);
		// var_dump($get_url['host']); die;
		switch ($get_url['host']) {
			case 'www.maxjizztube.com':
				return $this->get_info_video($url, 'www.maxjizztube.com');
				break;
			case 'www.4tube.com':
				return $this->get_info_video($url, 'www.4tube.com');
				break;
			case 'www.besthdtube.com':
				return $this->get_info_video($url, 'www.besthdtube.com');
				break;
			case 'lubetube.com':
				return $this->get_info_video($url, 'lubetube.com');
				break;
			case 'www.txxx.com':
				return $this->get_info_video($url, 'www.txxx.com');
				break;
			case 'www.pornhub.com':
				return $this->get_info_video($url, 'www.pornhub.com');
				break;
			case 'pornfun.com':
				return $this->get_info_video($url, 'pornfun.com');
				break;
			case 'www.vporn.com':
				return $this->get_info_video($url, 'www.vporn.com');
				break;
			case 'www.yobt.com':
				return $this->get_info_video($url, 'www.yobt.com');
				break;
			case 'www.youporn.com':
				return $this->get_info_video($url, 'www.youporn.com');
				break;
			case 'xhamster.com':
				return $this->get_info_video($url, 'xhamster.com');
				break;
			case 'www.xvideos.com':
				return $this->get_info_video($url, 'www.xvideos.com');
				break;
			case 'www.xtube.com':
				return $this->get_info_video($url, 'www.xtube.com');
				break;
			case 'fapbox.com':
				return $this->get_info_video($url, 'fapbox.com');
				break;
			case 'h2porn.com':
				return $this->get_info_video($url, 'h2porn.com');
				break;
			case 'upornia.com':
				return $this->get_info_video($url, 'upornia.com');
				break;

			default: return redirect('admincp/grab-video')->with('msg', 'Url is incorrect!');
		}
	}

	//grab youtube
	public function get_info_video($url, $host) {
		$data = $this->actionSimpleHtmlWithURL($url, $host);
		return $data;
	}

	public function actionSimpleHtmlWithURL($url, $host) {
		$cacheKey = 'VIDEO_URL_' . md5($url . $host);
		$data = Cache::get($cacheKey);

		if ($data) {
			return $data;
		}

		require base_path() . '/lib/simplehtmldom/simple_html_dom.php';

		try {
			$linkUrl        = '';
			$image          = '';
			$video_duration = '';
			$title          = '';
			$tagsWrapper    = '';

			//www.pornhub.com
			if ($host == 'www.pornhub.com') {

				$html_content = $this->get($url, 'http://www.pornhub.com', 'www.pornhub.com');

				$obj_content = str_get_html($html_content);
				if (!$obj_content) {
					throw new Exception("Error Processing Request From Pornhub Site", 1);
				}

				if ($obj_content->find('#player', 0) === NULL) {
					return false;
				}
				$linkURL = $obj_content->find('#player', 0)->innertext;


				if (strpos($linkURL, 'embedCode')) {
					$vtri = strpos($linkURL, 'embedCode');
					$link = substr($linkURL, $vtri + 0);
					$vtri1 = strpos($link, "'");
					// get Url Video
					$getEmbed = substr($link, 0, $vtri1);
				} else {
					return false;
				}
				//dd($getEmbed);
				if (strpos($getEmbed, 'src=')) {
					$vtri = strpos($getEmbed, 'src=');
					$link = substr($getEmbed, $vtri + 15);
					$vtri1 = strpos($link, '"');
					// get Url Video
					$embedCode = 'http://' . str_replace('\/', '/', substr($link, 0, $vtri1));
					$isEmbed = 'no';
					if (!empty($embedCode)) {
						$isEmbed = 'yes';
					}
				}

				// get Url Video
				if (strpos($linkURL, 'player_quality_480p')) {
					$vtri = strpos($linkURL, 'player_quality_480p');
					$link = substr($linkURL, $vtri + 23);
					$vtri1 = strpos($link, "'");
					// get Url Video
					$linkUrl = substr($link, 0, $vtri1);
				}
				if (empty($linkUrl)) {
					$vtri = strpos($linkURL, 'player_quality_240p');
					$link = substr($linkURL, $vtri + 23);
					$vtri1 = strpos($link, "'");
					// get Url Video
					$linkUrl = substr($link, 0, $vtri1);
				}
				$video_url = $url;
				// $start= date('Y-m-d H:i:s','1452585475');
				// $end = date('Y-m-d H:i:s','1452592675');
				//http://cdn2b.video.pornhub.phncdn.com/videos/201512/31/64985831/480P_600K_64985831.mp4?rs=200&ri=2500&ipa=115.79.59.63&s=1452582499&e=1452589699&h=13bf9f1d98da64cd3b6be3ba549dae2f
				//var_dump($linkUrl);die;
				// get duration Video
				if (strpos($linkURL, 'video_duration')) {
					$d_vtri = strpos($linkURL, 'video_duration');
					$dura = substr($linkURL, $d_vtri + 17);
					$d_vtri1 = strpos($dura, '"');
					// get duration Video
					$video_duration = (float) substr($dura, 0, $d_vtri1);
				}

				// get Title
				$title = $obj_content->find('.title-container h1.title', 0)->plaintext;
				// Tag
				$tagsWrapper = str_replace('Tags:&nbsp;', '', $obj_content->find('.tagsWrapper', 0)->plaintext);

				// get Image URL
				if (strpos($linkURL, 'image_url')) {
					$i_vtri = strpos($linkURL, 'image_url');
					$im = substr($linkURL, $i_vtri + 12);
					$i_vtri1 = strpos($im, '"');
					// get duration Video
					$image = substr($im, 0, $i_vtri1);
				}

				$description = '';
			}
			// pornfun.com
			if ($host == 'pornfun.com') {
				$html_content = $this->get($url, 'http://www.pornfun.com', 'pornfun.com');
				$obj_content = str_get_html($html_content);
				if (!$obj_content) {
					throw new Exception("Error Processing Request From PornFun Site", 1);
				}

				$linkURL = $obj_content->find('.player', 0)->innertext;
				// get Url Video
				if (strpos($linkURL, 'video_url')) {
					$vtri = strpos($linkURL, 'video_url');
					$link = substr($linkURL, $vtri + 12);
					$vtri1 = strpos($link, "'");
					// get Url Video
					$linkUrl = substr($link, 0, $vtri1);
				}
				// get duration Video
				$video_duration = $obj_content->find('.information strong', 0)->plaintext;

				// get Title
				$title = $obj_content->find('.headline h1', 0)->plaintext;
				// Tag
				$tags = $obj_content->find('meta[itemprop="keywords"]', 0)->outertext;
				if (strpos($tags, 'content')) {
					$tag_vitri = strpos($tags, 'content');
					$ta = substr($tags, $tag_vitri + 9);
					$tag_vitri1 = strpos($ta, '"');
					// get duration Video
					$tagsWrapper = substr($ta, 0, $tag_vitri1);
				}
				// get Image URL
				$ima = $obj_content->find('meta[itemprop="thumbnailUrl"]', 0)->outertext;
				if (strpos($ima, 'content')) {
					$i_vtri = strpos($ima, 'content');
					$im = substr($ima, $i_vtri + 9);
					$i_vtri1 = strpos($im, '"');
					// get duration Video
					$cutImage = explode('preview', substr($im, 0, $i_vtri1));
					$image = $cutImage[0] . '240x180/16' . end($cutImage);
					// var_dump($image); die;
				}
				//dd($image);
				// get description
				$decr = $obj_content->find('meta[itemprop="description"]', 0)->outertext;
				if (strpos($decr, 'content')) {
					$de_vitri = strpos($decr, 'content');
					$dec = substr($decr, $de_vitri + 9);
					$de_vitri1 = strpos($dec, '"');
					// get duration Video
					$description = substr($dec, 0, $de_vitri1);
				}
			}
			//www.txxx.com
			if ($host == 'www.txxx.com') {
				$video_url = $url;
				$html_content = $this->get($url, 'http://www.txxx.com', 'www.txxx.com');
				$obj_content = str_get_html($html_content);
				if (!$obj_content) {
					throw new Exception("Error Processing Request From Txxx Site", 1);
				}

				$linkUrl = $obj_content->find('.download-link a', 0)->href;

				$contentEmbeb = $obj_content->find('#share', 0)->innertext;
				$videoId = $obj_content->find('input[name=video_id]', 0)->value;
				$isEmbed = 'yes';
				$embedCode = 'http://www.txxx.com/embed/' . $videoId;

				// get duration Video
				$video_duration = trim($obj_content->find('.video-info__data strong', 0)->plaintext);
				// var_dump($video_duration);die;
				// get Title
				$title = $obj_content->find('.video-info__title h1', 0)->plaintext;

				// Tag

				$get_meta = get_meta_tags($url);
				$tagsWrapper = $get_meta['keywords'];
				//$obj_content->find('meta[itemprop="keywords"]',0)->outertext;

				$scripts = $obj_content->find('script');
				$image = '';
				foreach ($scripts as $key => $value) {
					$playlistPos = strpos($value->innertext, "playlist");
					if($playlistPos) {
						$playlistString = substr($value->innertext, $playlistPos);
						$imagePos = strpos($playlistString, 'image');
						$imageStringStart = substr($playlistString, $imagePos + 6);
						$imageStringEnd = substr($imageStringStart, 0, strpos($imageStringStart, ','));
						$image = trim(trim($imageStringEnd), '\'');
					}
				}

				$images = $obj_content->find('.player', 0)->innertext;


				// get Url Video
				if (strpos($images, 'preview_url')) {
					$vtri = strpos($images, 'preview_url');
					$link = substr($images, $vtri + 21);
					$vtri1 = strpos($link, "'");

					// get Url Video
					$imagethumb = substr($link, 0, $vtri1);
					$imageview = explode('preview.jpg', $imagethumb);
					$image = $imageview[0] . '220x165/1.jpg';
				}

				$description = $get_meta['description'];
			}

			//upornia.com
			if ($host == 'upornia.com') {
				$video_url = $url;
				$html_content = $this->get($url, 'http://upornia.com', 'upornia.com');
				$obj_content = str_get_html($html_content);
				if (!$obj_content) {
					throw new Exception("Error Processing Request From Upornia.com Site", 1);
				}

				$scripts = $obj_content->find('script');
				$linkUrl = '';
				foreach ($scripts as $sc) {
					$scInnerText = $sc->innertext;
					$jwPos = strpos($scInnerText, 'jwsettings');

					if($jwPos) {
						$jwText = substr($scInnerText, $jwPos);
						$sourcesPos = strpos($jwText, 'sources');
						$sourcesTextStart = substr($jwText, $sourcesPos + 8);
						$sourcesTextEnd = trim(substr($sourcesTextStart, 0, strpos($sourcesTextStart, ']') + 1));
						$formatSourceText = str_replace("'", '"', $sourcesTextEnd);
						$sources = json_decode($formatSourceText, true);
						$sourceFiles = [];
						if(!empty($sources)) {
							$sourceFiles = $sources[0];
							foreach ($sources as $file) {
								if($file['type'] == 'mp4') {
									$sourceFiles = $file;
								}
							}
						}
						if(!empty($sourceFiles)) {
							$linkUrl = $sourceFiles['file'];
						}
					}
				}

				$videoId = $obj_content->find('input[name=video_id]', 0)->value;
				$isEmbed = 'yes';
				$embedCode = 'http://upornia.com/embed/' . $videoId;
				// get duration Video
				$descriptions = $obj_content->find('.video-description .info .item');
				$clockString = '';
				foreach ($descriptions as $des) {
					$findClock = $des->find('.mdi-clock', 0);
					if($findClock) {
						$eleParent = $findClock->parent();
						$clockString = $eleParent->find('em', 0)->innertext;
					}
				}
				$minString = ''; $secondsString = ''; $video_duration = '';

				if(!empty($clockString)) {
					$durationArr = explode(' ', $clockString);
					$minString = intval($durationArr[0]);
					$secondsString = intval($durationArr[1]);
				}
				if($minString !== '' && $secondsString !== '') {
					$video_duration = $minString.':'.$secondsString;
				}

				// get Title
				$title = $obj_content->find('.mjs-videoinfo__title h1 span', 0)->plaintext;
				// Tag
				$get_meta = get_meta_tags($url);
				$tagsWrapper = $get_meta['keywords'];
				//$obj_content->find('meta[itemprop="keywords"]',0)->outertext;

				$image = '';
				$imagesElement = $obj_content->find('#myElement img', 0);

				if(!empty($imagesElement)) {
					$image = $imagesElement->src;
				}
				// dd($get_meta);
				$description = $get_meta['description'];
			}

			//lubetube.com
			if ($host == 'lubetube.com') {
				$html_content = $this->get($url, 'http://lubetube.com', 'lubetube.com');
				$obj_content = str_get_html($html_content);
				if (!$obj_content) {
					throw new Exception("Error Processing Request From Lubetube Site", 1);
				}

				$linkURL = $obj_content->find('#video-hd', 0)->href;
				$linkUrl = $linkURL;

				$video_url = $url;
				// get  Video script tag
				foreach ($obj_content->find('script') as $key => $value) {
					if ($key == 10) {
						$image = $value->outertext;
					}
				}
				if (strpos($image, 'video_preview_url')) {
					$vtri = strpos($image, 'video_preview_url');
					$link = substr($image, $vtri + 21);
					$vtri1 = strpos($link, '"');
					$image_url = substr($link, 0, $vtri1);
				}
				if (strpos($image, 'videopreview ')) {
					$vtri = strpos($image, 'videopreview ');
					$link = substr($image, $vtri + 16);
					$vtri1 = strpos($link, '"');
					$image_file = substr($link, 0, $vtri1);
				}
				$image = $image_url . "/" . $image_file; //image url
				$video_duration = $obj_content->find('.detailstitle'); //video duration
				foreach ($video_duration as $key => $value) {
					if ($key == 3) {
						$video_duration = $value->next_sibling()->innertext;
					}
					if ($key == 2) {
						$title = $value->next_sibling()->innertext;
					}
				}
				$get_meta = get_meta_tags($url); //get meta data video
				$tagsWrapper = $get_meta['keywords']; //video tag
				$description = $get_meta['description']; //video description
			}
			//www.besthdtube.com
			if ($host == 'www.besthdtube.com') {
				$html_content = $this->get($url, 'http://www.besthdtube.com', 'www.besthdtube.com');
				$obj_content = str_get_html($html_content);
				if (!$obj_content) {
					throw new Exception("Error Processing Request From Besthdtube Site", 1);
				}

				foreach ($obj_content->find('script') as $key => $value) { //get string
					if ($key == 13) {
						$string = $value->outertext;
					}
				}
				if (strpos($string, 'file')) {
					$vtri = strpos($string, 'file');
					$link = substr($string, $vtri + 7);
					$vtri1 = strpos($link, '"');
					$linkUrl = substr($link, 0, $vtri1); //video link
				}

				$video_url = $url;
				if (strpos($string, 'image')) {
					$vtri = strpos($string, 'image');
					$link = substr($string, $vtri + 8);
					$vtri1 = strpos($link, "'");
					$image = substr($link, 0, $vtri1); //image url
				}
				$title = $obj_content->find('title', 0)->plaintext;
				$video_duration = $obj_content->find('.time', 0)->plaintext; //video duration
				$get_meta = get_meta_tags($url); //meta data
				$tagsWrapper = $get_meta['keywords']; // video tag
				$description = $get_meta['description']; //video description
			}
			//www.4tube.com
			if ($host == 'www.4tube.com') {
				$html_content = $this->get($url, 'http://www.4tube.com', 'www.4tube.com');
				$obj_content = str_get_html($html_content);
				if (!$obj_content) {
					throw new Exception("Error Processing Request From 4tube Site", 1);
				}

				$data = $obj_content->find('#download-links');
				foreach ($data as $key) {
					$string_html = $key;
				}
				$attr = array();
				foreach ($string_html->find('button') as $key => $value) {
					$string = $value->attr;
					array_push($attr, $string);
				}
				//$data_id=$attr[0]['data-id'];
				for ($i = 0; $i < count($attr); $i++) {
					if ($attr[$i]['data-quality'] == "240") {
						$quality240 = $attr[$i]['data-quality'];
					}if ($attr[$i]['data-quality'] == "360") {
						$quality360 = $attr[$i]['data-quality'];
					}
					if ($attr[$i]['data-quality'] == "480") {
						$quality480 = $attr[$i]['data-quality'];
					}
					if ($attr[$i]['data-quality'] == "720") {
						$quality720 = $attr[$i]['data-quality'];
					}
					if ($attr[$i]['data-quality'] == "1080") {
						$quality1080 = $attr[$i]['data-quality'];
					}
				}
				// var_dump($quality240.$quality360.$quality480.$quality720.$quality1080);
				$v1080 = isset($quality1080) ? $quality1080 . '+' : '';
				$v720 = isset($quality720) ? $quality720 . '+' : '';
				$v480 = isset($quality480) ? $quality480 . '+' : '';
				$v360 = isset($quality360) ? $quality360 . '+' : '';
				$v240 = isset($quality240) ? $quality240 : '';
				$urlv = 'tkn.4tube.com/' . $attr[0]['data-id'] . '/desktop/' . $v1080 . $v720 . $v480 . $v360 . $v240;
				$ch = curl_init($urlv);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Origin: http://www.4tube.com'));
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$response = curl_exec($ch);
				curl_close($ch);
				$data_back = json_decode($response);
				//date('H:i:s ','20160109040025')
				$data = json_decode(json_encode($data_back), true);
				$linkUrl = $data['360']['token'];
				if (isset($data['720'])) {
					$linkUrlHD = $data['720']['token'];
				} else {
					$linkUrlHD = $linkUrl;
				}
				$video_url = $url;

				$str_image = $obj_content->find('.videoplayer');
				foreach ($str_image as $key) {
					foreach ($key->find('meta') as $keychild => $value) {
						if ($keychild == 0) {
							$temp_title = $value->outertext;
						}
						if ($keychild == 4) {
							$temp_image = $value->outertext;
						}
						if ($keychild == 4) {
							$temp_image = $value->outertext;
						}
						if (strlen($value->outertext) == 48) {
							$temp_duration = $value->outertext;
						}
					}
				}
				if (strpos($temp_title, 'content=')) {
					$vtri = strpos($temp_title, 'content=');
					$link = substr($temp_title, $vtri + 14);
					$vtri1 = strpos($link, '"');
					$title = substr($link, 0, $vtri1); //video link
				}
				if (strpos($temp_image, 'content=')) {
					$vtri = strpos($temp_image, 'content=');
					$link = substr($temp_image, $vtri + 9);
					$vtri1 = strpos($link, '"');
					$image = substr($link, 0, $vtri1); //video link
				}
				if (strpos($temp_duration, 'content=')) {
					$vtri = strpos($temp_duration, 'content=');
					$link = substr($temp_duration, $vtri + 9);
					$vtri1 = strpos($link, '"');
					$du_temp = substr($link, 0, $vtri1); //video link
				}
				$dura_strim_1 = substr($du_temp, 2);
				$dura_strim_2 = substr($dura_strim_1, 0, -1);
				$vowels = array("H", "M");
				$video_duration = str_replace($vowels, ':', $dura_strim_2); //video duration
				$get_meta = get_meta_tags($url); //get meta data video
				$tagsWrapper = ""; //$get_meta['keywords']; //video tag
				$description = $get_meta['description']; //video description
			}
			//www.maxjizztube.com
			if ($host == 'www.maxjizztube.com') {
				$html_content = $this->get($url, 'http://www.maxjizztube.com', 'www.maxjizztube.com');
				$obj_content = str_get_html($html_content);
				if (!$obj_content) {
					throw new Exception("Error Processing Request From Maxjizztube Site", 1);
				}

				$str_content = $obj_content->find('#player', 0)->outertext;
				if (strpos($str_content, 'settings')) {
					$vtri = strpos($str_content, 'settings');
					$link = substr($str_content, $vtri + 9);
					$vtri1 = strpos($link, '"');
					$link_temp = substr($link, 0, $vtri1);
				}
				//get data form URL
				$urlv = $link_temp;
				$ch = curl_init($urlv);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Origin: http://www.maxjizztube.com'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$response = curl_exec($ch);
				curl_close($ch);

				if (strpos($response, 'defaultVideo')) {
					$vtri = strpos($response, 'defaultVideo');
					$link = substr($response, $vtri + 13);
					$vtri1 = strpos($link, ';');
					$linkUrl = substr($link, 0, $vtri1);
				}

				$video_url = $url;
				if (strpos($str_content, 'overlay')) {
					$vtri = strpos($str_content, 'overlay');
					$link = substr($str_content, $vtri + 8);
					$vtri1 = strpos($link, '&');
					$image = substr($link, 0, $vtri1); //image url
				}
				$title = $obj_content->find('title', 0)->plaintext;

				$video_duration = "08:00"; //video duration
				$get_meta = get_meta_tags($url); //meta data
				$tagsWrapper = $get_meta['keywords']; // video tag
				$description = $get_meta['description']; //video description
			}
			//www.vporn.com
			if ($host == 'www.vporn.com') {
				$html_content = $this->get($url, 'http://www.vporn.com/', 'www.vporn.com');
				$obj_content = str_get_html($html_content);
				if (!$obj_content) {
					throw new Exception("Error Processing Request From Vporn Site", 1);
				}

				$linkURL = $obj_content->find('.video_panel', 0)->innertext;
				// get Url Video
				if (strpos($linkURL, 'videoUrlMedium')) {
					$vtri = strpos($linkURL, 'videoUrlMedium');
					$link = substr($linkURL, $vtri + 18);
					$vtri1 = strpos($link, '"');
					$linkUrl = substr($link, 0, $vtri1);
				}
				if (empty($linkUrl)) {
					$vtri = strpos($linkURL, 'videoUrlLow');
					$link = substr($linkURL, $vtri + 15);
					$vtri1 = strpos($link, '"');
					$linkUrl = substr($link, 0, $vtri1);
				}
				// get duration Video
				$video_duration = $obj_content->find('.info-runtime', 1)->plaintext;
				if (strpos($video_duration, 'Runtime')) {
					$vtri = strpos($video_duration, 'Runtime');
					$link = substr($video_duration, $vtri + 11);
					$link = trim($link);
					$video_duration = str_replace(array('min', 'sec', ' '), array(':', '', ''), $link);
				}

				// get Title
				$title = $obj_content->find('#vtitle', 0)->plaintext;
				// Tag
				$tagsWrapper = $obj_content->find('.tagas-secondrow', 0)->plaintext;
				// get Image URL
				if (strpos($linkURL, 'imageUrl')) {
					$vtri = strpos($linkURL, 'imageUrl');
					$link = substr($linkURL, $vtri + 12);
					$vtri1 = strpos($link, '"');
					$image = substr($link, 0, $vtri1);
					$image = 'http://www.vporn.com' . $image;
				}
				// get description
				$description = $obj_content->find('.descr', 0)->plaintext;
			}
			//www.yobt.com
			if ($host == 'www.yobt.com') {
				$html_content = $this->get($url, 'http://www.yobt.com/', 'www.yobt.com');
				$obj_content = str_get_html($html_content);
				if (!$obj_content) {
					throw new Exception("Error Processing Request From Yobt Site", 1);
				}

				$linkURL = $obj_content->find('.player', 0)->innertext;
				// var_dump($linkURL); die;
				// get Url Video
				if (strpos($linkURL, "video'   : '")) {
					$vtri = strpos($linkURL, "video'   : '");
					$link = substr($linkURL, $vtri + 12);
					$vtri1 = strpos($link, "'");
					$linkUrl = substr($link, 0, $vtri1);
				}
				// get duration Video
				if (strpos($linkURL, "runtime' : ")) {
					$vtri = strpos($linkURL, "runtime' : ");
					$link = substr($linkURL, $vtri + 11);
					$vtri1 = strpos($link, ",");
					$video_duration = (float) substr($link, 0, $vtri1);
				}
				// get Title
				$title = $obj_content->find('#post-title', 0)->plaintext;
				// Tag
				$tagsWrapper = $obj_content->find('#post-tags', 0)->plaintext;
				// get Image URL
				if (strpos($linkURL, "cover'   : ")) {
					$vtri = strpos($linkURL, "cover'   : ");
					$link = substr($linkURL, $vtri + 12);
					$vtri1 = strpos($link, "'");
					$image = substr($link, 0, $vtri1);
				}
				// get description
				$description = '';
			}
			//www.youporn.com
			if ($host == 'www.youporn.com') {
				$html_content = $this->get($url, 'http://www.youporn.com/', 'www.youporn.com');
				$obj_content = str_get_html($html_content);
				if (!$obj_content) {
					throw new Exception("Error Processing Request From Youporn Site", 1);
				}

				$linkURL = $obj_content->find('#videoWrapper', 0)->innertext;
				// var_dump($linkURL); die;
				// get Url Video
				if (strpos($linkURL, "720: '")) {
					$vtri = strpos($linkURL, "720: '");
					$link = substr($linkURL, $vtri + 6);
					$vtri1 = strpos($link, "'");
					// get Url Video
					$linkUrl = substr($link, 0, $vtri1);
				}
				if (empty($linkUrl)) {
					if (strpos($linkURL, "480: '")) {
						$vtri = strpos($linkURL, "480: '");
						$link = substr($linkURL, $vtri + 6);
						$vtri1 = strpos($link, "'");
						// get Url Video
						$linkUrl = substr($link, 0, $vtri1);
					}
					if (empty($linkUrl)) {
						$vtri = strpos($linkURL, "240: '");
						$link = substr($linkURL, $vtri + 6);
						$vtri1 = strpos($link, "'");
						// get Url Video
						$linkUrl = substr($link, 0, $vtri1);
					}
				}
				$video_url = $url;
				// get duration Video
				if (strpos($linkURL, "videoDuration ")) {
					$vtri = strpos($linkURL, "videoDuration ");
					$link = substr($linkURL, $vtri + 16);
					$vtri1 = strpos($link, ",");
					$video_duration = (float) substr($link, 0, $vtri1);
				}
				// get Title
				if (strpos($linkURL, "videoTitle")) {
					$vtri = strpos($linkURL, "videoTitle");
					$link = substr($linkURL, $vtri + 13);
					$vtri1 = strpos($link, '"');
					$title = substr($link, 0, $vtri1);
				}
				// Tag
				$tagsWrapper = '';

				// get Image URL
				if (strpos($linkURL, "poster")) {
					$vtri = strpos($linkURL, "poster");
					$link = substr($linkURL, $vtri + 8);
					$vtri1 = strpos($link, '"');
					$image = substr($link, 0, $vtri1);
				}
				$toArray = explode('/original/', $image);
				$preview = explode('/', end($toArray));
				$digitsSuffix = end($preview);
				$digitsPreffix = $preview[0];

				// get description
				$description = '';
			}
			//xhamster.com
			if ($host == 'xhamster.com') {
				$html_content = $this->get($url, 'http://xhamster.com/', 'xhamster.com');
				$obj_content = str_get_html($html_content);
				if (!$obj_content) {
					throw new Exception("Error Processing Request From Xhamster Site", 1);
				}

				$linkURL = $obj_content->find('#playerSwf', 0)->innertext;
				// get link
				$linkUrl = $obj_content->find('.noFlash a', 0)->href;
				//dd($linkURL);
				$video_url = $url;
				// get duration Video
				if (strpos($linkURL, 'duration":')) {
					$vtri = strpos($linkURL, 'duration":');
					$link = substr($linkURL, $vtri + 11);
					$vtri1 = strpos($link, ",");
					$video_duration = (int) substr($link, 0, $vtri1);
				}
				//dd($video_duration);
				if (strpos($linkURL, 'spriteUrl":')) {
					$vtri = strpos($linkURL, 'spriteUrl":');
					$link = substr($linkURL, $vtri + 12);
					$vtri1 = strpos($link, '",');
					$preview = str_replace('\/', '/', substr($link, 0, $vtri1));
				}
				// get Title
				$title = $obj_content->find('#playerBox .head h1', 0)->plaintext;
				// Tag
				$tagsWrapper = '';
				// get Image URL
				$image = $obj_content->find('.noFlash img', 0)->src;
				// get description
				$description = '';
			}
			//www.xvideos.com
			if ($host == 'www.xvideos.com') {
				$html_content = $this->get($url, 'http://www.xvideos.com/', 'www.xvideos.com');
				$obj_content = str_get_html($html_content);
				$linkURL = $obj_content->find('#player', 0)->innertext;
				if (!$obj_content) {
					throw new Exception("Error Processing Request From Xvideos Site", 1);
				}

				foreach ($obj_content->find('script') as $key => $value) { //get string
					if ($key == 7) {
						$string = $value->outertext;
					}
				}

				$getEmbed = $obj_content->find('#tabEmbed', 0)->innertext;

				if (strpos($getEmbed, 'src')) {
					$vtri = strpos($getEmbed, 'src');
					$link = substr($getEmbed, $vtri + 10);
					$vtri1 = strpos($link, '&quot;');
					$embedCode = substr($link, 0, $vtri1); //video link
					$isEmbed = 'no';
					if (!empty($embedCode)) {
						$isEmbed = 'yes';
					}
				}

				if (strpos($string, 'http')) {
					$vtri = strpos($string, 'http');
					$link = substr($string, $vtri + 0);
					$vtri1 = strpos($link, "'");
					$linkUrlMobile = substr($link, 0, $vtri1); //video link
				}
				//var_dump($linkURL); die;
				// get link
				if (strpos($linkURL, "flv_url")) {
					$vtri = strpos($linkURL, "flv_url");
					$link = substr($linkURL, $vtri + 8);
					$vtri1 = strpos($link, "&amp;");
					$linkUrl = substr($link, 0, $vtri1);
					$linkUrl = $this->change_text($linkUrl);
					// var_dump($linkUrl); die;
				}

				$video_url = $url;
				// get duration Video

				$video_duration = $obj_content->find('.duration', 0)->plaintext;
				$video_duration = str_replace('-', '', trim($video_duration));
				$video_duration = strimConvertTime(trim($video_duration));

				// // get Title
				$title = $obj_content->find('title', 0)->plaintext;
				// Tag
				$tag = $obj_content->find('meta[name="keywords"]', 0)->outertext;
				if (strpos($tag, 'content')) {
					$de_vitri = strpos($tag, 'content');
					$dec = substr($tag, $de_vitri + 9);
					$de_vitri1 = strpos($dec, '"');
					// get duration Video
					$tagsWrapper = substr($dec, 0, $de_vitri1);
				}
				// get Image URL
				if (strpos($linkURL, "url_bigthumb")) {
					$vtri = strpos($linkURL, "url_bigthumb");
					$link = substr($linkURL, $vtri + 13);
					$vtri1 = strpos($link, "&amp;");
					$image = substr($link, 0, $vtri1);
					$image = $this->change_text($image);
				}
				$previews = explode('.', $image);
				$digitsSuffix = end($previews);
				$digitsPreffix = $previews[3];
				// get description
				$decr = $obj_content->find('meta[name="description"]', 0)->outertext;
				if (strpos($decr, 'content')) {
					$de_vitri = strpos($decr, 'content');
					$dec = substr($decr, $de_vitri + 9);
					$de_vitri1 = strpos($dec, '"');
					// get duration Video
					$description = substr($dec, 0, $de_vitri1);
				}
			}
			//www.xtube.com
			if ($host == 'www.xtube.com') {
				$html_content = $this->get($url, 'http://www.xtube.com/', 'www.xtube.com');
				$obj_content = str_get_html($html_content);
				if (!$obj_content) {
					throw new Exception("Error Processing Request From Xtube Site", 1);
				}

				$linkURL = $obj_content->find('#watchPageLeft', 0)->innertext;
				// var_dump($linkURL); die;
				// get link
				if (strpos($linkURL, "flashvars.video_url")) {
					$vtri = strpos($linkURL, "flashvars.video_url");
					$link = substr($linkURL, $vtri + 23);
					$vtri1 = strpos($link, '"');
					$linkUrl = substr($link, 0, $vtri1);
					$linkUrl = $this->change_text($linkUrl);
					// var_dump($linkUrl); die;
				}

				// get duration Video
				// $video_duration = $obj_content->find('.duration',0)->plaintext;
				if (strpos($linkURL, "video_duration")) {
					$vtri = strpos($linkURL, "video_duration");
					$link = substr($linkURL, $vtri + 18);
					$vtri1 = strpos($link, '"');
					$video_duration = substr($link, 0, $vtri1);
				}
				// flashvars.video_duration
				// // get Title
				$title = $obj_content->find('#videoDetails .title', 0)->plaintext;

				// Tag
				$tag = $obj_content->find('#videoDetails .fields', 0)->plaintext;
				$tag = trim($tag);
				$tagsWrapper = str_replace(' ', '', $tag);

				// get Image URL
				if (strpos($linkURL, "timeline_preview_url")) {
					$vtri = strpos($linkURL, "timeline_preview_url");
					$link = substr($linkURL, $vtri + 29);
					$vtri1 = strpos($link, '"');
					$image = substr($link, 0, $vtri1);
					$image = str_replace(array('{', '}'), array('', ''), $image);
				}
				// get description
				$description = $obj_content->find('#videoDetails .fieldsDesc', 0)->plaintext;
			}

			//fapbox.com
			if ($host == 'fapbox.com') {
				$html_content = $this->get($url, 'http://fapbox.com/', 'fapbox.com');
				$obj_content = str_get_html($html_content);
				if (!$obj_content) {
					throw new Exception("Error Processing Request From Fapbox Site", 1);
				}

				//dd($obj_content);
				$script = $obj_content->find('script');
				$stringData = '';
				foreach ($script as $key => $value) {
					if(strpos($value->innertext, "file")) {
						$stringData = $value->innertext;
					}
				}

				if (!empty($stringData)) {
					$vtri = strpos($stringData, "file");
					$link = substr($stringData, $vtri + 6);
					$vtri1 = strpos($link, '"');
					$linkUrl = substr($link, 0, $vtri1);
					// var_dump($linkUrl); die;
				}

				// get duration Video
				// $video_duration = $obj_content->find('.duration',0)->plaintext;
				if (strpos($stringData, "length")) {
					$vtri = strpos($stringData, "length");
					$link = substr($stringData, $vtri + 9);
					$vtri1 = strpos($link, '"');
					$video_duration = substr($link, 0, $vtri1);
				}
				// flashvars.video_duration
				// // get Title
				$title = $obj_content->find('.page-title', 0)->plaintext;


				// Tag
				$get_meta = get_meta_tags($url); //get meta data video
				$tagsWrapper = $get_meta['keywords']; //video tag
				$description = $get_meta['description']; //video description
				// get Image URL
				//
				if (strpos($stringData, "img")) {
					$vtri = strpos($stringData, "img");
					$link = substr($stringData, $vtri + 6);
					$vtri1 = strpos($link, '"');
					$image = substr($link, 0, $vtri1);
				}
				$imagePreview = explode('-3b', $image);
				$digitsSuffix = $imagePreview[0];
				$digitsPreffix = $imagePreview[1];
				//dd($imagePreview);
				if (strpos($stringData, "src")) {
					$vtri = strpos($stringData, "src");
					$link = substr($stringData, $vtri + 5);
					$vtri1 = strpos($link, "'");
					$embedCode = substr($link, 0, $vtri1);
					$isEmbed = 'no';
					if (!empty($embedCode)) {
						$isEmbed = 'yes';
					}
				}

				$video_url = $url;
			}

			//h2porn.com
			if ($host == 'h2porn.com') {
				$html_content = $this->get($url, 'http://h2porn.com/', 'h2porn.com');
				$obj_content = str_get_html($html_content);
				if (!$obj_content) {
					throw new Exception("Error Processing Request From H2porn Site", 1);
				}

				//dd($obj_content);
				$script = $obj_content->find('script');
				$stringData = '';
				foreach ($script as $key => $value) {
					if(strpos($value->innertext, "video_url")) {
						$stringData = $value->innertext;
					}
				}

				if (!empty($stringData)) {
					$vtri = strpos($stringData, "video_url");
					$link = substr($stringData, $vtri + 12);
					$vtri1 = strpos($link, "'");
					$linkUrl = substr($link, 0, $vtri1);
					// var_dump($linkUrl); die;
				}

				// get duration Video
				$getDuration = $obj_content->find('.media-stats-item .icon-time', 0);
				if(!empty($getDuration)) {
					$parentDuration = $getDuration->parent();
					$durationText = $parentDuration->innertext;

					$video_duration = substr($durationText, strpos($durationText, '</i>')+4);
				}

				// flashvars.video_duration
				// // get Title
				$title = $obj_content->find('h1.media-title', 0)->plaintext;
				// Tag
				$get_meta = get_meta_tags($url.'/'); //get meta data video

				$tagsWrapper = $get_meta['keywords']; //video tag
				$description = $get_meta['description']; //video description
				// get Image URL
				//
				if (strpos($stringData, "preview_url")) {
					$vtri = strpos($stringData, "preview_url");
					$link = substr($stringData, $vtri + 14);
					$vtri1 = strpos($link, "'");
					$image = substr($link, 0, $vtri1);
				}

				if ( !empty($image) ) {
					$imagePreview =  explode('preview.mp4', $image);
					$image = $imagePreview[0] . '240x180/1' . $imagePreview[1];
				}

				//dd($imagePreview);
				if (strpos($stringData, "src")) {
					$vtri = strpos($stringData, "src");
					$link = substr($stringData, $vtri + 5);
					$vtri1 = strpos($link, '"');
					$embedCode = substr($link, 0, $vtri1);
					$isEmbed = 'no';
					if (!empty($embedCode)) {
						$isEmbed = 'yes';
					}
				}

				$video_url = $url;
			}
			// dd($linkUrl, $image, $video_duration, $title, $tagsWrapper);
			//www.spankwire.com
			if( empty($linkUrl) || empty($image) || empty($video_duration) || empty($title) || empty($tagsWrapper) ) {
				throw new Exception("Error Processing Request", 1);
			}
			$data = array(
				'link'          => $linkUrl,
				'linkHD'        => isset($linkUrlHD) ? $linkUrlHD : '',
				'video_url'     => isset($video_url) ? $video_url : '',
				'duration'      => $video_duration,
				'title'         => $title,
				'tag'           => $tagsWrapper,
				'image'         => $image,
				'isEmbed'       => isset($isEmbed) ? $isEmbed : 'no',
				'embedCode'     => isset($embedCode) ? $embedCode : '',
				'description'   => $description,
				'digitsSuffix'  => isset($digitsSuffix) ? $digitsSuffix : '',
				'digitsPreffix' => isset($digitsPreffix) ? $digitsPreffix : '',
				'preview'       => isset($preview) ? $preview : '',
				'mobileVideo'   => isset($linkUrlMobile) ? $linkUrlMobile : ''
			);
			// dd($data);

			//add cache
			//cache in 30m
			Cache::put($cacheKey, $data, 30);
		} catch (Exception $e) {
			// die('die');
			Log::error(date('Y-m-d H:i:s') . ' - ERR: ' . $e->getMessage());
		}

		// var_dump($data);die;
		return $data;
	}

	public function change_text($text) {
		$need = array('%2F', '%3A', '%3D', '%3F', '%26', '%25');
		$replace = array('/', ':', '=', '?', '&', '%');
		$text = str_replace($need, $replace, $text);
		return $text;
	}

	static function get($url, $referer = '', $host = '') {

		if (empty($referer)) {
			$info = pathinfo($url);
			$referer = $info['dirname'];
			$host = get_domain($referer);
		}
		//$proxy = '50.30.34.30:8888';

		$header[0] = "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8";
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection:keep-alive";
		$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$header[] = "Accept-Language:en-US,en;q=0.8,vi;q=0.6";
		// $header[] = "Expect:";
		$header[] = "Pragma: "; // browsers keep this blank.
		$header[] = "Host:{$host}"; // browsers keep this blank.
		$header[] = "Referer: {$referer}"; // browsers keep this blank.
		// dd($header);

		try {
			$objCurl = curl_init();

			if (FALSE === $objCurl)
				throw new Exception('failed to initialize');

			//curl_setopt($objCurl, CURLOPT_PROXY, $proxy);
			curl_setopt($objCurl, CURLOPT_URL, $url);
			// curl_setopt($objCurl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36");
			// curl_setopt($objCurl, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36");
			curl_setopt($objCurl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17");
			curl_setopt($objCurl, CURLOPT_HTTPHEADER, $header);
			curl_setopt($objCurl, CURLOPT_ENCODING, 'gzip,deflate,sdch,br');
			curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, 1);
			// curl_setopt($objCurl, CURLOPT_AUTOREFERER, TRUE);
			curl_setopt($objCurl, CURLOPT_FOLLOWLOCATION, FALSE);
			// curl_setopt($objCurl, CURLOPT_VERBOSE, TRUE);
			curl_setopt($objCurl, CURLOPT_TIMEOUT, 7200);
			// curl_setopt($objCurl, CURLOPT_COOKIEFILE, $cookie);
			// curl_setopt($objCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
			// curl_setopt($objCurl, CURLOPT_SSL_VERIFYHOST, FALSE);

			$result = curl_exec($objCurl);

			$err    = curl_errno( $objCurl );
			$errmsg = curl_error( $objCurl );
			if (FALSE === $objCurl) {
				throw new Exception($errmsg, $err);
			}

			$header_info = curl_getinfo( $objCurl );
			curl_close( $objCurl );

			$header_info['errno']   = $err;
			$header_info['errmsg']  = $errmsg;
			$header_info['content'] = $result;

		} catch(Exception $e) {
			trigger_error(sprintf(
				'Curl failed with error #%d: %s',
				$e->getCode(), $e->getMessage()),
			E_USER_ERROR);
		}
		// dd($header_info);
		return $result;
	}

	public function post_import_video(Request $get) {
		$file = Input::File('csv_file');
		$string_name = mt_rand();
		$path_import = public_path() . "/upload/video/";
		$tubeWeb = VideoModel::$tubeCopWeb;

		if ($file) {
			$extension = $file->getClientOriginalExtension();

			$allowedFormat = array("csv", "CSV", "json", "JSON");

			$destinationPath = $path_import;

			$filename = "" . $string_name . "." . $extension;

			if (in_array($extension, $allowedFormat)) {
				$file->move($destinationPath, $filename);
				$data_array = array();

				if (file_exists($path_import . $string_name . "." . $extension) == true) {

					if($extension=="csv"){
						$csv_file = fopen($path_import . $string_name . ".csv", "r");

						while (!feof($csv_file)) {
							if(in_array($get->website, $tubeWeb)) {
								$json = fgetcsv($csv_file,0, '|');
							} else {
								$json = fgetcsv($csv_file);
							}

							array_push($data_array, $json);
						}
						fclose($csv_file);
					}elseif($extension=="json"){
						$json_file = file_get_contents($path_import.$filename);
						$json_a = json_decode($json_file, true);
						$data_array = $json_a['videos'];
					}


				} else {
					return redirect('admincp/dowload-video-add-view')->with('msgerror', 'Couldn\'t read file!');
				}

				if ($get->website == "www.pornhub.com") {
					foreach ($data_array as $key => $result) {

						$result[0] = str_replace(array('"|"', '"|', '|"'), '|', $result[0]);

						$toArray = explode('|', $result[0]);

						$itemsCount = count($result);

						$lastItem = str_replace(array('"|"', '"|', '|"'), '|', $result[$itemsCount - 1]);

						$detail = explode('|', $lastItem);

						if (isset($toArray) && count($toArray) >= 6 && isset($detail) && count($detail) >= 4 ) {
							if (strpos($toArray[0], 'src=')) {
								$vtri = strpos($toArray[0], 'src=');
								$link = substr($toArray[0], $vtri + 5);
								$vtri1 = strpos($link, '"');
								// get Url Video
								$embedCode = substr($link, 0, $vtri1);
								$isEmbed = 'no';
								if (!empty($embedCode)) {
									$isEmbed = 'yes';
								}
							}

							$tags = "";
							for ($i=1; $i < ($itemsCount - 1); $i++) {
								$tags .= $result[$i];
								$tags = ($i + 2) < $itemsCount ? $tags . "," : $tags;
							}
							$tags=str_replace(";", " ", $tags);

							$data = array(
								'website'    => $get->website,
								'embedCode'  => $embedCode,
								'isEmbed'    => $isEmbed,
								'tag'        => $tags,
								'video_url'  => $toArray[1],
								'categories' => $toArray[2],
								'rating'     => $toArray[3],
								'uploadby'   => $toArray[4],
								'title'      => $toArray[5],
								'duration'   => $detail[1],
								'pornstar'   => $detail[2],
								'poster'     => $detail[3],
							);
							$this->get_content_video($get, $data);
						}
					}
				}
				if ($get->website == "www.redtube.com") {
					unset($data_array[0]);
					foreach ($data_array as $key => $result) {

						$result[0] = str_replace(array('"|"', '"|', '|"'), '|', $result[0]);

						$toArray = explode('|', $result[0]);

						if (isset($toArray) && count($toArray) == 11 ) {

							$tags = str_replace(";", ",", strtolower($toArray[6]));

							preg_match('/src="([^"]+)"/', $toArray[1], $match);
							$embedCode = $match[1];

							$isEmbed = !empty($embedCode)?"yes":"no";


							$poster = explode(";", $toArray[2])[0];

							//duration to secon
							$duration = str_replace('m',':',$toArray[8]);
							$duration = str_replace('s','',$duration);
							$_pre = substr_count($duration, ':')==0?"0:0:":(substr_count($duration, ':')==1?"0:":"");
							$duration = $_pre.$duration;
							$duration= strtotime($duration) - strtotime('TODAY');


							$data = array(
								'website'    => $get->website,
								'embedCode'  => $embedCode,
								'isEmbed'    => $isEmbed,
								'tag'        => $tags,
								'video_url'  => $toArray[3],
								'categories' => $toArray[5],
								'rating'     => 0,
								'uploadby'   => "",
								'title'      => $toArray[4],
								'duration'   => $duration,
								'pornstar'   => $toArray[7],
								'poster'     => $poster,
							);
							$this->get_content_video($get, $data);

						}
						else if (isset($toArray) && count($toArray) == 10) { // new format

							try {

								$tags = str_replace(";", ",", strtolower($toArray[5]));

								//preg_match('/src="([^"]+)"/', $toArray[1], $match);
								//$embedCode = $match[1];
								$embedCode = '';

								$isEmbed = !empty($embedCode)?"yes":"no";


								$poster = explode(";", $toArray[1])[0];

								//duration to secon
								$duration = str_replace('m', ':', $toArray[7]);
								$duration = str_replace('s', '', $duration);
								$_pre = substr_count($duration, ':') == 0 ? "0:0:" : (substr_count($duration, ':') == 1 ? "0:" : "");
								$duration = $_pre . $duration;
								$duration = strtotime($duration) - strtotime('TODAY');


								$data = array(
									'website'    => $get->website,
									'embedCode'  => $embedCode,
									'isEmbed'    => $isEmbed,
									'tag'        => $tags,
									'video_url'  => $toArray[2],
									'categories' => $toArray[4],
									'rating'     => 0,
									'uploadby'   => "",
									'title'      => $toArray[3],
									'duration'   => $duration,
									'pornstar'   => $toArray[6],
									'poster'     => $poster,
								);
							} catch(Exception $ex){
								return dump($ex);
							}

							$this->get_content_video($get, $data);
						}
					}
				}
				if ($get->website == "www.tube8.com") {
					foreach ($data_array as $key => $result) {

						$toArray = explode('|', $result[0]);

						if (isset($toArray) && count($toArray) == 11) {

							preg_match('/src="([^"]+)"/', $toArray[0], $match);
							$embedCode = $match[1];

							$isEmbed = !empty($embedCode)?"yes":"no";

							$tags = str_replace(";", ",", strtolower($toArray[6]));

							$data = array(
								'website'    => $get->website,
								'embedCode'  => $embedCode,
								'isEmbed'    => $isEmbed,
								'tag'        => $tags,
								'video_url'  => $toArray[1],
								'categories' => $toArray[2],
								'rating'     => $toArray[3],
								'uploadby'   => $toArray[4],
								'title'      => $toArray[5],
								'duration'   => $toArray[7],
								'pornstar'   => "",
								'poster'     => $toArray[9],
							);
							$this->get_content_video($get, $data);
						}
					}
				}
				if ($get->website == "www.youporn.com") {
					foreach ($data_array as $key => $result) {

						$toArray = explode('|', $result[0]);

						if (isset($toArray) && count($toArray) >= 11) {

							preg_match("/src='([^']+)'/", $toArray[0], $match);

							$embedCode = str_replace('watch', 'embed', $match[1]);

							$isEmbed = !empty($embedCode)?"yes":"no";

							$tags = str_replace(";", ",", strtolower($toArray[6]));

							$data = array(
								'website'    => $get->website,
								'embedCode'  => 'https://'.$get->website.$embedCode,
								'isEmbed'    => $isEmbed,
								'tag'        => $tags,
								'video_url'  => 'https://'.$get->website.$toArray[1],
								'categories' => $toArray[2],
								'rating'     => $toArray[3],
								'uploadby'   => $toArray[4],
								'title'      => $toArray[5],
								'duration'   => $toArray[7],
								'pornstar'   => $toArray[8],
								'poster'     => $toArray[9],
							);

							$this->get_content_video($get, $data);
						}
					}
				}
				if ($get->website == "www.xtube.com") {
					foreach ($data_array as $key => $result) {

						$toArray = explode('|', $result[0]);

						if (isset($toArray) && count($toArray) >= 9) {

							$embedCode = $toArray[0];

							$isEmbed = !empty($embedCode)?"yes":"no";

							$tags = str_replace(";", ",", strtolower($toArray[6]));

							$data = array(
								'website'    => $get->website,
								'embedCode'  => $embedCode,
								'isEmbed'    => $isEmbed,
								'tag'        => $tags,
								'video_url'  => $toArray[1],
								'categories' => $toArray[2],
								'rating'     => $toArray[3],
								'uploadby'   => $toArray[4],
								'title'      => $toArray[5],
								'duration'   => $toArray[7],
								'pornstar'   => '',
								'poster'     => $toArray[8],
							);
							$this->get_content_video($get, $data);
						}
					}
				}
				// dd($data_array);
				if (in_array($get->website, $tubeWeb) && count($data_array) > 1) {
					require base_path() . '/lib/simplehtmldom/simple_html_dom.php';

					foreach ($data_array as $key => $result) {
						if(count($result) >= 13 && isset($result[11])) {
							$embedIframe = str_get_html($result[11]);

							if(!empty($embedIframe) && !empty($embedIframe->find('iframe', 0))) {
								$embedCode = $embedIframe->find('iframe', 0)->src;
								$isEmbed = !empty($embedCode)?"yes":"no";
								$tags = str_replace(";", ",", strtolower($result[9]));

								$data = array(
									'website'    => $get->website,
									'embedCode'  => $embedCode,
									'isEmbed'    => $isEmbed,
									'tag'        => $tags,
									'video_url'  => $result[7],
									'categories' => $result[8],
									'rating'     => null,
									'uploadby'   => null,
									'title'      => $result[1],
									'duration'   => $result[10],
									'pornstar'   => null,
									'poster'     => $result[12],
								);
								// dd($data);
								$this->get_content_video($get, $data);
							}
						}
					}
				}

				// if (in_array($get->website, $tubeWeb) && count($data_array) > 1) {
				// 	require base_path() . '/lib/simplehtmldom/simple_html_dom.php';
				// 	//remove first header
				// 	$exchangeKeyMap = array_flip($data_array[0]);
				// 	$headerItem = array_shift($data_array);

				// 	foreach ($data_array as $key => $result) {
				// 		if(isset($exchangeKeyMap['Embed']) && isset($result[$exchangeKeyMap['Embed']])) {
				// 			$embedIframe = str_get_html($result[$exchangeKeyMap['Embed']]);

				// 			if(!empty($embedIframe) && !empty($embedIframe->find('iframe', 0))) {
				// 				$embedCode = $embedIframe->find('iframe', 0)->src;
				// 				$isEmbed = !empty($embedCode)?"yes":"no";
				// 				$tags = str_replace(";", ",", strtolower($result[$exchangeKeyMap['Tags']]));

				// 				$data = array(
				// 					'website'    => $get->website,
				// 					'embedCode'  => $embedCode,
				// 					'isEmbed'    => $isEmbed,
				// 					'tag'        => $tags,
				// 					'video_url'  => $result[$exchangeKeyMap['URL']],
				// 					'categories' => isset($exchangeKeyMap['Categories']) && isset($result[$exchangeKeyMap['Categories']]) ? $result[$exchangeKeyMap['Categories']] : '',
				// 					'rating'     => null,
				// 					'uploadby'   => null,
				// 					'title'      => $result[$exchangeKeyMap['VideoTitle']],
				// 					'duration'   => $result[$exchangeKeyMap['Duration']],
				// 					'pornstar'   => isset($exchangeKeyMap['Models']) && isset($result[$exchangeKeyMap['Models']]) ? $result[$exchangeKeyMap['Models']] : '',
				// 					'poster'     => $result[$exchangeKeyMap['Screenshot']],
				// 				);

				// 				$this->get_content_video($get, $data);
				// 			}
				// 		}
				// 	}
				// }

				$success = "Save video success";
				return redirect('admincp/video')->with('success', $success);
			} else {
				return redirect('admincp/dowload-video-add-view')->with('msgerror', 'File type not allowed!Ex: ' . implode(", ", $allowedFormat));
			}
		}
		session(['addvideotype'=>'import']);
		return redirect()->back()->withErrors(['Please choose file to import']);
	}

	public function get_content_video($get_data, $data) {
		// $video = VideoModel::where("video_url", "=", "%" . $data['video_url'] . "%")->orWhere("video_src", "=", "%" . $data['video_url'] . "%")->first();
		// $video = VideoModel::where("video_url", $data['video_url'])->orWhere("video_src", $data['video_url'])->first();
		//var_dump(video);die;
		// if (empty($video)) {
			$string = mt_rand();
			$tostring = ($get_data->post_result_cat_import != NULL) ? implode(',', $get_data->post_result_cat_import) : '';

			$model                   = new VideoModel();
			// $model->categories_Id = $data->category;
			$model->categories_Id    = $tostring;
			$model->upTime           = time();
			$model->cat_id           = $tostring != '' ? cat_form_array($tostring) : '';
			$model->pornstar_Id      = $get_data->pornstar_import;
			$model->channel_Id       = $get_data->channel_import;
			$model->title_name       = $data['title'];
			$model->post_name        = str_slug($data['title']);
			$model->video_url        = $data['video_url'];
			$model->isEmbed          = $data['isEmbed'];
			$model->embedCode        = $data['embedCode'];
			$model->poster           = str_replace('\/', '/', $data['poster']);
			$model->tag              = $data['tag'];
			$model->string_Id        = $string;
			$model->comment_status   = 1;
			//var_dump($result['duration']."/".(int)$result['duration']);die;
			$dura                    = (int) $data['duration'];
			$duration                = $dura;
			$model->duration         = $duration;
			$model->status           = $get_data->status;
			$model->website          = $data['website'];
			$model->dowloader        = 1;

			if($model->save() && $tostring != '') {
				$cat_post = cat_array($tostring);
				for ($i = 0; $i < count($cat_post); $i++) {
					$videocat = new \App\Models\VideoCatModel();
					$videocat->video_id = $string;
					$videocat->cat_id = $cat_post[$i];
					$videocat->save();
				}
			}
		// }
	}

}
