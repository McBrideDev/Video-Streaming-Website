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
use App\Models\OptionModel;
use App\Models\VideoSettingModel;
use App\Models\EmailSettingModel;
use App\Models\EmailTempleteModel;
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

class VideoSettingController extends Controller
{
	public function get_setting (){
		$get_config= VideoSettingModel::first();
		return view('admincp.ads.video_setting')->with('v_setting',$get_config);
	}
	public function post_setting(Request $get){
		if($get){
			$data=[
				"is_subscribe"=>$get->is_subscribe,
				"is_favorite"=>$get->is_favorite,
				"is_download"=>$get->is_download,
				"is_embed"=>$get->is_embed,
				"is_ads"=>$get->is_ads,
				"time_skip_ads"=>$get->time_skip_ads,
				"video_reload"=>$get->video_reload
			];
			$player_logo=Input::file('player_logo');
			$player_loading=Input::file('player_loading');
			if($player_logo){
				$file = array('playerLogo' => Input::file('player_logo'));

				$rules = array(
					'playerLogo' => 'mimes:jpeg,png',
				);

				$validator = Validator::make($file, $rules);
				if ($validator->fails()) {
					return redirect('admincp/video-setting')->with('msgerror',$validator->errors()->all()[0]);
				}
				$extension =$player_logo->getClientOriginalExtension();

				$notAllowed = array("exe","php","asp","pl","bat","js","jsp","sh");

				$destinationPath = public_path()."/upload/player/";

				$filename = "player_logo.".$extension;

				if(!in_array($extension,$notAllowed)) {
						$player_logo->move($destinationPath, $filename);
						$addchannel =VideoSettingModel::where('id','=',$get->id)->update(array('player_logo'=>$filename));
				}
			}

			if($player_loading){
				$file = array('playerLoading' => Input::file('player_loading'));

				$rules = array(
					'playerLoading' => 'mimes:gif',
				);

				$validator = Validator::make($file, $rules);
				if ($validator->fails()) {
					return redirect('admincp/video-setting')->with('msgerror',$validator->errors()->all()[0]);
				}
				$extension =$player_loading->getClientOriginalExtension();

				$notAllowed = array("exe","php","asp","pl","bat","js","jsp","sh");

				$destinationPath = public_path()."/upload/player/";

				$filename = "player_loading.".$extension;

				if(!in_array($extension,$notAllowed)) {
						$player_loading->move($destinationPath, $filename);
						$addchannel =VideoSettingModel::where('id','=',$get->id)->update(array('player_loading' => $filename));
				}
			}
			$update_setting=  VideoSettingModel::find($get->id);
			$update_setting->is_subscribe = $get->is_subscribe;
			$update_setting->is_favorite = $get->is_favorite;
			$update_setting->is_download = $get->is_download;
			$update_setting->is_embed = $get->is_embed;
			$update_setting->is_ads = $get->is_ads;
			$update_setting->time_skip_ads = $get->time_skip_ads;
			$update_setting->video_reload = $get->video_reload;
			if($update_setting->save()){
				return redirect('admincp/video-setting')->with('msg','Update setting successfully!');
			}
			return redirect('admincp/video-setting')->with('msgerror','Update setting not complete!');
		}
	}

	public function get_in_player_media_ads(){

		return view('admincp.ads.add_video_ads')->with('title_pornstar','Manage Video Ads ');
	}
	public function post_in_player_media_ads(Request $get) {
		if($get){
			$rules = [
				'title' => 'required|min:5',
				'descr' => 'required',
				'adv_url' => 'required|url',
				'media' => 'required',
			];
			$validator = Validator::make($get->all(), $rules);
			if($validator->fails()){
				return back()->withErrors($validator)->withInput();
			}
			$sting_name=mt_rand();
			$title= $get->title;
			$descr= $get->descr;
			$adv_url= $get->adv_url;
			$status= $get->status;
			$media=Input::file('media');
			if($media){
				$extension =$media->getClientOriginalExtension();

				$notAllowed = array("exe","php","asp","pl","bat","js","jsp","sh","docx","doc","pdf","xls","xlsx");

				$date=date('Y-m-d');
				if(!is_dir(base_path()."/adv")){
					mkdir(base_path()."/adv", 0777, true);
				}

				$folder=base_path()."/adv/".$date."";
				if(!is_dir($folder)){
					$folder = mkdir(base_path()."/adv/" . $date , 0777, true);
					$upload_folder = base_path()."/adv/".$date."/";
				}else{
					$upload_folder = base_path()."/adv/".$date."/";
				}
				//dd($media);
				$filename = $sting_name.".".$extension;

				if(!in_array($extension,$notAllowed)) {
					$media->move($upload_folder, $filename);
					$add= new VideoAdsModel();
					$add->title=$title;
					$add->string_id=$sting_name;
					$add->descr=$descr;
					$add->adv_url=$adv_url;
					$add->status=$status;
					$add->media= "/adv/".$date."/".$filename;

					$get_duration= "ffprobe -v quiet -of csv=p=0 -show_entries format=duration ".base_path().'/adv/'.$date.'/'.$filename;
					$processConvertDuration = new Process($get_duration);
					$processConvertDuration->setTimeout(7200);
					$processConvertDuration->run();
					$duration = '00:00:30';
					if ($processConvertDuration->isSuccessful()) {
						$duration= '00:'.sec2hms($processConvertDuration->getOutput());
					}

					if($add->save()){
						//create xml
						$dom = new \DOMDocument('1.0','UTF-8');
						//VAST tag
						$avst = $dom->createElement('VAST');
						$dom->appendChild($avst);
						//$avst->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
						$avst->setAttribute('version','2.0');
						// $avst->setAttribute('xsi:noNamespaceSchemaLocation','vast3_draft.xsd');
						//Ad tag
						$ad = $dom->createElement('Ad');
						$avst->appendChild($ad);
						$ad->setAttribute('id','static');
						// $ad->setAttribute('sequence','1');
						//InLine
						$inline=$dom->createElement('InLine');
						$ad->appendChild($inline);
						$AdSystem=$dom->createElement('AdSystem');
						$inline->appendChild($AdSystem);
						// $AdSystem->setAttribute('version','2.0');
						$AdSystem->appendChild($dom->createTextNode('Adult Streaming Website'));

						$AdTitle=$dom->createElement('AdTitle');
						$inline->appendChild($AdTitle);
						$AdTitle->appendChild($dom->createTextNode('In player Ads'));

						$Impression=$dom->createElement('Impression');
						$inline->appendChild($Impression);
						$Impression->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));

						$Creatives=$dom->createElement('Creatives');
						$inline->appendChild($Creatives);

						$Creative=$dom->createElement('Creative');
						$Creatives->appendChild($Creative);
						$Creative->setAttribute('sequence','1');

						$Linear =$dom->createElement('Linear');
						$Linear->setAttribute('skipoffset', '00:00:07');
						$Creative->appendChild($Linear);

						$Duration =$dom->createElement('Duration');
						$Linear->appendChild($Duration);
						$Duration->appendChild($dom->createTextNode($duration));

						$TrackingEvents=$dom->createElement('TrackingEvents');
						$Linear->appendChild($TrackingEvents);
						//loop Tracking
						$Tracking =$dom->createElement('Tracking');
						$TrackingEvents->appendChild($Tracking);
						$Tracking->setAttribute('event','start');
						$Tracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));
						
						$Tracking =$dom->createElement('Tracking');
						$TrackingEvents->appendChild($Tracking);
						$Tracking->setAttribute('event','firstQuartile');
						$Tracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));
						
						$Tracking =$dom->createElement('Tracking');
						$TrackingEvents->appendChild($Tracking);
						$Tracking->setAttribute('event','midpoint');
						$Tracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));
						
						$Tracking =$dom->createElement('Tracking');
						$TrackingEvents->appendChild($Tracking);
						$Tracking->setAttribute('event','thirdQuartile');
						$Tracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));

						$Tracking =$dom->createElement('Tracking');
						$TrackingEvents->appendChild($Tracking);
						$Tracking->setAttribute('event','complete');
						$Tracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));

						$Tracking =$dom->createElement('Tracking');
						$TrackingEvents->appendChild($Tracking);
						$Tracking->setAttribute('event','pause');
						$Tracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));

						$Tracking =$dom->createElement('Tracking');
						$TrackingEvents->appendChild($Tracking);
						$Tracking->setAttribute('event','mute');
						$Tracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));

						$Tracking =$dom->createElement('Tracking');
						$TrackingEvents->appendChild($Tracking);
						$Tracking->setAttribute('event','fullscreen');
						$Tracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));

						// end loop
						$VideoClicks =$dom->createElement('VideoClicks');
						$Linear->appendChild($VideoClicks);

						$ClickThrough=$dom->createElement('ClickThrough');
						$VideoClicks->appendChild($ClickThrough);
						$ClickThrough->appendChild($dom->createTextNode(''.$adv_url.''));

						$ClickTracking=$dom->createElement('ClickTracking');
						$VideoClicks->appendChild($ClickTracking);
						$ClickTracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));

						$MediaFiles=$dom->createElement('MediaFiles');
						$Linear->appendChild($MediaFiles);

						$MediaFile=$dom->createElement('MediaFile');
						$MediaFiles->appendChild($MediaFile);
						$MediaFile->setAttribute('id','1');
						$MediaFile->setAttribute('delivery','progressive');
						$MediaFile->setAttribute('type','video/mp4');
						$MediaFile->setAttribute('bitrate','400');
						$MediaFile->setAttribute('width','640');
						$MediaFile->setAttribute('height','360');
						$MediaFile->appendChild($dom->createTextNode(asset('/adv/'.$date.'/'.$filename)));

						$Creative=$dom->createElement('Creative');
						$Creatives->appendChild($Creative);
						$Creative->setAttribute('sequence','1');

						$CompanionAds =$dom->createElement('CompanionAds');
						$Creative->appendChild($CompanionAds);

						$companion = $dom->createElement('Companion');
						$companion->setAttribute('id', '1');
						$companion->setAttribute('width', '300');
						$companion->setAttribute('height', '250');
						$CompanionAds->appendChild($companion);

						$staticResource = $dom->createElement('StaticResource');
						$staticResource->setAttribute('creativeType', 'image/png');
						$staticResource->appendChild($dom->createTextNode(asset('logo.png')));
						$companion->appendChild($staticResource);

						$companionClickThrough = $dom->createElement('CompanionClickThrough');
						$companionClickThrough->appendChild($dom->createTextNode($adv_url));
						$companion->appendChild($companionClickThrough);

						$companion = $dom->createElement('Companion');
						$companion->setAttribute('id', '2');
						$companion->setAttribute('width', '728');
						$companion->setAttribute('height', '90');
						$CompanionAds->appendChild($companion);

						$staticResource = $dom->createElement('StaticResource');
						$staticResource->setAttribute('creativeType', 'image/png');
						$staticResource->appendChild($dom->createTextNode(asset('logo.png')));
						$companion->appendChild($staticResource);

						$companionClickThrough = $dom->createElement('CompanionClickThrough');
						$companionClickThrough->appendChild($dom->createTextNode($adv_url));
						$companion->appendChild($companionClickThrough);

						$savefile =file_put_contents(base_path().'/adv/'.$date.'/'.$sting_name.'.xml', $dom->saveXML());
						//create xml
						if($savefile){
							return redirect('admincp/ads-video')->with('msg','Add successfully!');
						}
					}
				}
				return redirect('admincp/in-player-media-ads')->with('msgerror','File is not allowed!');
			}
		}
	}

	public function get_video_ads(){
		$getads= VideoAdsModel::get();
		return view('admincp.ads.video_ads_list')->with('videoAds',$getads);
	}

	public function get_add_video_ads(){
		$get_video_all = VideoModel::where('status','=', VideoModel::STATUS_COMPLETED)->Orderby('total_view','DESC')->get();
		return view('admincp.ads.add_video_ads')->with('video',$get_video_all)->with('title_pornstar','Manage Video Ads ');
	}

	public function get_edit_video_ads($id){
		if($id!=NULL && $id!=""){
			$get_video_all = VideoModel::where('status','=', VideoModel::STATUS_COMPLETED)->Orderby('total_view','DESC')->get();
			$checkAds= VideoAdsModel::find($id);
			if($checkAds!=NULL){
				return view('admincp.ads.edit_video_ads')->with('editvideoads',$checkAds)->with('video',$get_video_all)->with('title_pornstar','Manage Video Ads ');
			}
			return redirect('admincp/ads-video')->with('msg-error','Ads not found !');
		}
		return redirect('admincp/ads-video')->with('msg-error','Ads not found !');
	}

	public function post_edit_video_ads(Request $get,$id){
		if($get){
			$string=$get->string;
			$data=array(
				'title'=>$get->title,
				'descr'=>$get->descr,
				'adv_url'=>$get->adv_url,
				'string_id'=>$get->string,
				'status'=>$get->status
			);
			$media=Input::file('media');

			if($media) {
				$extension =$media->getClientOriginalExtension();

				$notAllowed = array("exe","php","asp","pl","bat","js","jsp","sh","docx","doc","pdf","xls","xlsx");

				$date=date('Y-m-d');
				if(!is_dir(base_path()."/adv")){
					mkdir(base_path()."/adv", 0777, true);
				}
				$folder= base_path()."/adv/".$date."";
				if(!is_dir($folder)){
					$folder = mkdir(base_path()."/adv/" . $date , 0777, true);
					$upload_folder = base_path()."/adv/".$date."";
				}else{
					$upload_folder = base_path()."/adv/".$date."";
				}

				$filename = "".$string.".".$extension;

				if(!in_array($extension,$notAllowed)) {
					$media->move($upload_folder, $filename);
					
					$get_duration= "ffprobe -v quiet -of csv=p=0 -show_entries format=duration ".base_path().'/adv/'.$date.'/'.$filename;
					$processConvertDuration = new Process($get_duration);
					$processConvertDuration->setTimeout(7200);
					$processConvertDuration->run();
					$duration = '00:00:30';
					if ($processConvertDuration->isSuccessful()) {
						$duration= '00:'.sec2hms($processConvertDuration->getOutput());
					}

					$update_media= VideoAdsModel::where('id','=',$id)->update(array('media'=> "/adv/".$date."/".$filename));
					if($update_media) {
						//create xml
						$dom = new \DOMDocument('1.0','UTF-8');
						//VAST tag
						$avst = $dom->createElement('VAST');
						$dom->appendChild($avst);
						//$avst->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
						$avst->setAttribute('version','2.0');
						// $avst->setAttribute('xsi:noNamespaceSchemaLocation','vast3_draft.xsd');
						//Ad tag
						$ad = $dom->createElement('Ad');
						$avst->appendChild($ad);
						$ad->setAttribute('id','static');
						// $ad->setAttribute('sequence','1');
						//InLine
						$inline=$dom->createElement('InLine');
						$ad->appendChild($inline);
						$AdSystem=$dom->createElement('AdSystem');
						$inline->appendChild($AdSystem);
						// $AdSystem->setAttribute('version','2.0');
						$AdSystem->appendChild($dom->createTextNode('Adult Streaming Website'));

						$AdTitle=$dom->createElement('AdTitle');
						$inline->appendChild($AdTitle);
						$AdTitle->appendChild($dom->createTextNode('In player Ads'));

						$Impression=$dom->createElement('Impression');
						$inline->appendChild($Impression);
						$Impression->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));

						$Creatives=$dom->createElement('Creatives');
						$inline->appendChild($Creatives);

						$Creative=$dom->createElement('Creative');
						$Creatives->appendChild($Creative);
						$Creative->setAttribute('sequence','1');

						$Linear =$dom->createElement('Linear');
						$Linear->setAttribute('skipoffset', '00:00:07');
						$Creative->appendChild($Linear);

						$Duration =$dom->createElement('Duration');
						$Linear->appendChild($Duration);
						$Duration->appendChild($dom->createTextNode($duration));

						$TrackingEvents=$dom->createElement('TrackingEvents');
						$Linear->appendChild($TrackingEvents);
						//loop Tracking
						$Tracking =$dom->createElement('Tracking');
						$TrackingEvents->appendChild($Tracking);
						$Tracking->setAttribute('event','start');
						$Tracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));
						
						$Tracking =$dom->createElement('Tracking');
						$TrackingEvents->appendChild($Tracking);
						$Tracking->setAttribute('event','firstQuartile');
						$Tracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));
						
						$Tracking =$dom->createElement('Tracking');
						$TrackingEvents->appendChild($Tracking);
						$Tracking->setAttribute('event','midpoint');
						$Tracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));
						
						$Tracking =$dom->createElement('Tracking');
						$TrackingEvents->appendChild($Tracking);
						$Tracking->setAttribute('event','thirdQuartile');
						$Tracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));

						$Tracking =$dom->createElement('Tracking');
						$TrackingEvents->appendChild($Tracking);
						$Tracking->setAttribute('event','complete');
						$Tracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));

						$Tracking =$dom->createElement('Tracking');
						$TrackingEvents->appendChild($Tracking);
						$Tracking->setAttribute('event','pause');
						$Tracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));

						$Tracking =$dom->createElement('Tracking');
						$TrackingEvents->appendChild($Tracking);
						$Tracking->setAttribute('event','mute');
						$Tracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));

						$Tracking =$dom->createElement('Tracking');
						$TrackingEvents->appendChild($Tracking);
						$Tracking->setAttribute('event','fullscreen');
						$Tracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));

						// end loop
						$VideoClicks =$dom->createElement('VideoClicks');
						$Linear->appendChild($VideoClicks);

						$ClickThrough=$dom->createElement('ClickThrough');
						$VideoClicks->appendChild($ClickThrough);
						$ClickThrough->appendChild($dom->createTextNode($get->adv_url));

						$ClickTracking=$dom->createElement('ClickTracking');
						$VideoClicks->appendChild($ClickTracking);
						$ClickTracking->appendChild($dom->createTextNode('http://demo.jwplayer.com/static-tag/pixel.gif'));

						$MediaFiles=$dom->createElement('MediaFiles');
						$Linear->appendChild($MediaFiles);

						$MediaFile=$dom->createElement('MediaFile');
						$MediaFiles->appendChild($MediaFile);
						$MediaFile->setAttribute('id','1');
						$MediaFile->setAttribute('delivery','progressive');
						$MediaFile->setAttribute('type','video/mp4');
						$MediaFile->setAttribute('bitrate','400');
						$MediaFile->setAttribute('width','640');
						$MediaFile->setAttribute('height','360');
						$MediaFile->appendChild($dom->createTextNode(asset('/adv/'.$date.'/'.$filename)));

						$Creative=$dom->createElement('Creative');
						$Creatives->appendChild($Creative);
						$Creative->setAttribute('sequence','1');

						$CompanionAds =$dom->createElement('CompanionAds');
						$Creative->appendChild($CompanionAds);

						$companion = $dom->createElement('Companion');
						$companion->setAttribute('id', '1');
						$companion->setAttribute('width', '300');
						$companion->setAttribute('height', '250');
						$CompanionAds->appendChild($companion);

						$staticResource = $dom->createElement('StaticResource');
						$staticResource->setAttribute('creativeType', 'image/png');
						$staticResource->appendChild($dom->createTextNode(asset('logo.png')));
						$companion->appendChild($staticResource);

						$companionClickThrough = $dom->createElement('CompanionClickThrough');
						$companionClickThrough->appendChild($dom->createTextNode($get->adv_url));
						$companion->appendChild($companionClickThrough);

						$companion = $dom->createElement('Companion');
						$companion->setAttribute('id', '2');
						$companion->setAttribute('width', '728');
						$companion->setAttribute('height', '90');
						$CompanionAds->appendChild($companion);

						$staticResource = $dom->createElement('StaticResource');
						$staticResource->setAttribute('creativeType', 'image/png');
						$staticResource->appendChild($dom->createTextNode(asset('logo.png')));
						$companion->appendChild($staticResource);

						$companionClickThrough = $dom->createElement('CompanionClickThrough');
						$companionClickThrough->appendChild($dom->createTextNode($get->adv_url));
						$companion->appendChild($companionClickThrough);
						

						$savefile =file_put_contents(base_path().'/adv/'.$date.'/'.$string.'.xml', $dom->saveXML());
						if($savefile){
							return redirect('admincp/ads-video')->with('msg','Update successfully!');
						}
					}
				}
				return redirect('edit_in-player-media-ads/'.$id.'')->with('msgerror','File is not allowed!');
			}

			$update_field= VideoAdsModel::where('id','=',$id)->update($data);
			if($update_field){
				return redirect('admincp/ads-video')->with('msg','Edit successfully!');
			}
		}
	}

	public function del_video_ads($id){
		if($id!=NULL or $id!= ""){
			$checkAds= VideoAdsModel::where('id','=',$id)->first();
			if($checkAds!=NULL){
				$media = explode('/', $checkAds->media);
				//dd($media);
				$path = $media[1].'/'.$media[2];
				$del_xml=unlink(base_path().'/'.$media[1].'/'.$media[2].'/'.$checkAds->string_id.'.xml');
				$del_media=unlink(base_path().'/'.$checkAds->media.'')  ;
				if($del_xml && $del_media){
					$deleteAds= VideoAdsModel::where('id','=',$id)->delete();
					if($deleteAds){
						return redirect('admincp/ads-video')->with('msg-success','Delete Ads successfuly');
					}
				}
				return redirect('admincp/ads-video')->with('msg-error','Ads  not found !');

			}
			return redirect('admincp/ads-video')->with('msg-error','Ads  not found !');
		}
		return redirect('admincp/ads-video')->with('msg-error','Ads  not found !');
	}

	public function post_list_video_ads(Request $post) {
		$start = $post->start;
		$length = $post->length;
		$col = $post->columns;
		$order = $post['order'][0];
		$orderby = $col[$order['column']]['data'];
		$orderby = $orderby==1?"title":$orderby;
		$sortasc = $order['dir'];
		$criteria = "1=1";
		if($col[1]['search']['value']!="") {
			$keyword = $col[1]['search']['value'];
			$criteria = "table_video_ads.title LIKE '%".$keyword."%'";
		}
		$recordsTotal = VideoAdsModel::count();
		$recordsFiltered = VideoAdsModel::select('id')
						->whereRaw($criteria)
						->count();
		$get_list = VideoAdsModel::select('video_ads.*')
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

	public function delete_all_video_ads($string_id) {
		$get = VideoAdsModel::whereRaw('id IN(' . $string_id . ')')->get();

		$deletedAds = true;
		if ($get) {
			foreach ($get as $key => $value) {
				$media = explode('/', $value->media);
				//dd($media);
				$path      = $media[1].'/'.$media[2];
				$del_xml   = unlink(base_path().'/'.$media[1].'/'.$media[2].'/'.$value->string_id.'.xml');
				$del_media = unlink(base_path().'/'.$value->media.'');
				if($del_xml && $del_media){
					$checker = $value->delete();
					if (!$checker)
						$deletedAds = false;
				}
			}
			if ($deletedAds) {
				return redirect('admincp/ads-video')->with('msg', 'Deleted ads successfully!');
			} else {
				return redirect('admincp/ads-video')->with('msgerror', 'Deleted ads unsuccessfully!');
			}
		}
		return redirect('admincp/ads-video')->with('msgerror', 'Request not found !');
	}


	public function get_text_ads(){
		$getads= VideoTextAdsModel::Orderby('status','DESC')->get();
		return view('admincp.ads.text_list')->with('textAds',$getads);
	}

	public function get_add_text_ads(){
		$get_video_all = VideoModel::where('status','=', VideoModel::STATUS_COMPLETED)->Orderby('total_view','DESC')->get();
		return view('admincp.ads.add_textads')->with('video',$get_video_all)->with('title_pornstar','Manage Text Ads ');
	}

	public function post_add_text_ads(Request $get){
		$rules = [
			'ads_title'   => 'required|min:5' ,
			'ads_content' => 'required|min:5' ,
			'return_url'  => 'required|url',
		];

		$validator = Validator::make($get->all(), $rules);
		if($validator->fails()){
			return back()->withErrors($validator)->withInput();
		}

		$addAds= new VideoTextAdsModel();
		$addAds->ads_title=$get->ads_title;
		$addAds->ads_content= $get->ads_content;
		$addAds->return_url=$get->return_url;
		$addAds->status=$get->status;
		if($addAds->save()){
			return redirect('admincp/ads-text-video')->with('msg-success','Add Ads successfuly !');
		}
	}

	public function get_edit_text_ads($id){
		if($id!=NULL){
			$check= VideoTextAdsModel::where('id','=',$id)->first();
			$get_video_all = VideoModel::where('status','=', VideoModel::STATUS_COMPLETED)->Orderby('total_view','DESC')->get();
			if($check!=NULL){
				return view('admincp.ads.edit_textads')->with('editads',$check)->with('video',$get_video_all)->with('title_pornstar','Manage Text Ads');
			}
			return redirect('admincp/ads-text-video')->with('msg-error','Ads Not Found !');
		}
	}

	public function post_edit_text_ads(Request $get,$id){
		if($id !=NULL && $get!=null){
			$data=array(
				"ads_title"   =>$get->ads_title,
				"ads_content" => $get->ads_content,
				"return_url"  =>$get->return_url,
				"status"      =>$get->status
			);
			$getupdate= VideoTextAdsModel::where('id','=',$id)->update($data);
			if($getupdate){
				return redirect('admincp/ads-text-video')->with('msg-success','Update successfuly !');
			}else{
				return redirect('edit-video-text-ads&is='.$id.'')->with('msg-error','Update not successfuly.Please try again !');
			}
		}
	}

	public function del_text_ads($id){
		if($id!=NULL){
			$check= VideoTextAdsModel::where('id','=',$id)->first();
			if($check!=NULL){
				$delAds=  VideoTextAdsModel::where('id','=',$id)->delete();
				if($delAds){
					return redirect('admincp/ads-text-video')->with('msg-success','Delete Ads successfuly !');
				}else{
					return redirect('admincp/ads-text-video')->with('msg-error','Delete Ads not successfuly !');
				}
			}else{

				return redirect('admincp/ads-text-video')->with('msg-error','Ads not found !');
			}
		}
	}

	public function post_list_text_ads(Request $post) {
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
			$criteria = "table_video_ads_text.ads_title LIKE '%".$keyword."%'";
		}
		$recordsTotal = VideoTextAdsModel::count();
		$recordsFiltered = VideoTextAdsModel::select('id')
						->whereRaw($criteria)
						->count();
		$get_list = VideoTextAdsModel::select('video_ads_text.*')
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

	public function delete_all_text_ads($string_id) {
		$deletedAds = VideoTextAdsModel::whereRaw('id IN(' . $string_id . ')')->delete();

		if ($deletedAds) {
			return redirect('admincp/ads-text-video')->with('msg', 'Deleted ads successfully!');
		} else {
			return redirect('admincp/ads-text-video')->with('msgerror', 'Deleted ads unsuccessfully!');
		}
	}

	public function get_email_templete(){
		$get_list =EmailTempleteModel::get();
		return view('admincp.mail.email_list')->with('email_temp',$get_list);

	}
	public function get_add_email_templete(){
		return view('admincp.mail.add_email_templete')->with('firstname','{{$firstname}}')
		->with('lastname','{{$lastname}}')
		->with('site_name','{{$site_name}}')
		->with('site_email','{{$site_email}}')
		->with('site_phone','{{$site_phone}}')
		->with('channel_name','{{$channel_name}}')
		->with('newpassword','{{$newpassword}}')
		->with('token','{{$token}}')
		->with('email','{{$email}}')
		->with('content','{{$content}}');

	}
	public function post_add_email_templete(Request $get){
		if($get){
			$name=$get->name;
			$name_slug=str_slug($get->name,'_');
			$description=$get->description;
			$content=$get->content;
			$status=1;
			$add_temp=new EmailTempleteModel();
			$add_temp->name=$name;
			$add_temp->description=$description;
			$add_temp->content=$content;
			$add_temp->name_slug=$name_slug;
			$add_temp->status=$status;
			if($add_temp->save()){
				$email_templete_file = fopen(base_path()."/resources/views/admincp/mail/".$name_slug.".blade.php", "w") or die("Unable to open file!");
				$html = ($content);
				fwrite($email_templete_file, $html);
				fclose($email_templete_file);
				return redirect('admincp/email-templete')->with('msg','Email templete add successfully!');
			}else{
				return redirect('admincp/email-templete')->with('msgerror','Insert not complete!')->with('data',$get);
			}
		}
	}

	public function get_edit_email_templete($id){
		if($id!=NULL){
			$check= EmailTempleteModel::find($id);
			if($check!=NULL){
				return view('admincp.mail.edit_email_templete')->with('edit_temp',$check)->with('firstname','{{$firstname}}')
				->with('lastname','{{$lastname}}')
				->with('site_name','{{$site_name}}')
				->with('site_email','{{$site_email}}')
				->with('site_phone','{{$site_phone}}')
				->with('channel_name','{{$channel_name}}')
				->with('newpassword','{{$newpassword}}')
				->with('token','{{$token}}')
				->with('email','{{$email}}')
				->with('content','{{$content}}')
				->with('title_pornstar','Email Templates');
			}
			return redirect('admincp/email-templete')->with('msgerror','Request not fount');
		}
		return redirect('admincp/email-templete')->with('msgerror','Request not fount');
	}

	public function post_edit_email_templete(Request $get,$id){
		if($get){
			if($id!=NULL){
				$name_slug=$get->name_slug;
				$content=$get->content;
				$data=array(
					'name'=>$get->name,
					'name_slug'=>str_slug($get->name,'_'),
					'description'=>$get->description,
					'content'=>$get->content,
					'status'=>1,
				);
				$update_temp=EmailTempleteModel::where('id','=',$id)->update($data);
				if($update_temp){
					$email_templete_file = fopen(base_path()."/resources/views/admincp/mail/".$name_slug.".blade.php", "w") or die("Unable to open file!");
					$html = ($content);
					fwrite($email_templete_file, $html);
					fclose($email_templete_file);
					return redirect('admincp/email-templete')->with('msg','Email templete update successfully!');
				}
				return redirect('admincp/edit-email-templete&id='.$id.'')->with('msgerror','Update not complete.');
			}
			return redirect('admincp/email-templete')->with('msgerror','Request not fount');
		}
		return redirect('admincp/email-templete')->with('msgerror','Request not fount');
	}

	public function get_del_email_templete($id){
		if($id!=NULL){
			$check=EmailTempleteModel::find($id);
			if($check!=NULL){
				if($check->delete()){
					unlink(base_path()."/resources/views/admincp/mail/".$check->name_slug.".blade.php");
					return redirect('admincp/email-templete')->with('msg','Email templete delete successfully!');
				}
				return redirect('admincp/email-templete')->with('msgerror','Request not fount!');
			}
			return redirect('admincp/email-templete')->with('msgerror','Request not fount!');
		}
	}

	public function  get_email_setting(){
		$get_email_setting =EmailSettingModel::first();
		$list_temp= EmailTempleteModel::get();
		return view('admincp.mail.email_setting')->with('temp',$list_temp)->with('email_setting',$get_email_setting) ;
	}

	public function post_email_setting(Request $get){
		if($get){
			$data=[
				"registration_email"           =>$get->registration_email,
				"admin_forgot_password_email"  =>$get->admin_forgot_password_email,
				"member_forgot_password_email" =>$get->member_forgot_password_email,
				"channel_subscriber_email"     =>$get->channel_subscriber_email,
				"channel_register_email"       =>$get->channel_register_email,
				"reply_comment_email"          =>$get->reply_comment_email,
			];
			$update= EmailSettingModel::where('id','=',$get->id)->update($data);
			if($update){
				return redirect('admincp/email-setting')->with('msg','Update successfully !');
			}
			return redirect('admincp/email-setting')->with('msgerror','Update not complete !');
		}
	}
}
