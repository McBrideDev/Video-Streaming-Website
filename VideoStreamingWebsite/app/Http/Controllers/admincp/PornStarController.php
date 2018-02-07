<?php

namespace App\Http\Controllers\admincp;
use App\Http\Controllers\Controller;
use App\Services\Modules\Modules;
use App\Models\PornStarModel;
use App\Models\PornStarSubscriberModel;
use App\Models\ActivityLogModel;
use App\Models\PornStarPhotoModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
class PornStarController extends Controller
{

	public function get_pornstar(){
		$pornstar = PornStarModel::orderBy('title_name', 'DESC')->get();
		if($pornstar){
			return view('admincp.pornstar.index')->with('pornstar',$pornstar);

		}
	}
	public function get_editpornstar($id){
		$editpornstar = PornStarModel::find ($id);
		if($editpornstar)
		{
			return view('admincp.pornstar.edit')->with('editpornstar',$editpornstar)->with('title_pornstar','Manage an Exisitng Pornstar');
		}
	}

	public function post_editpornstar(Request $get, $id){
		$rules= [
			'title_name'  => 'required|min:5',
			'hair_color' => 'alpha',
			'eye_color' => 'alpha'
		];
		$validator = Validator::make($get->all(), $rules);
		if($validator->fails()){
			return back()->withErrors($validator)->withInput();
		}

		$check_name=PornStarModel::where('id','=',$id);
		if($check_name->first()!=NULL) {
			$addpornstar =$check_name->update(array(
				'title_name' => $_POST["title_name"],
				'post_name'  => str_slug($_POST["title_name"],"-"),
				'description' => $_POST["description"],
				'subscribe_status' => isset($_POST["subscribe_status"]) ? 1 : 0 ,
				'status' =>$_POST["status"],
				'gender'=>$_POST["gender"],
				'age'=>$_POST['age'],
				'born'=>$_POST['born'],
				'height'=>$_POST['height'],
				'ethnicity'=>$_POST['ethnicity'],
				'hair_color'=>$_POST['hair_color'],
				'eye_color'=>$_POST['eye_color']
			));
			$file = Input::File('poster');
			$wall = Input::File('wall_poster');
			if($file) {
				$extension =$file->getClientOriginalExtension();

				$notAllowed = array("exe","php","asp","pl","bat","js","jsp","sh","doc","docx","xls","xlsx");

				$destinationPath = public_path()."/upload/pornstar/";

				$filename = "Pornstar_Poster_".str_slug($_POST["title_name"],'_').".".$extension;

				if(!in_array($extension,$notAllowed))
				{
						$file->move($destinationPath, $filename);
						$addpornstar =PornStarModel::where('id','=',$id)->update(array('poster'=>$filename));

				} else{
					return redirect('admincp/edit-pornstar/'.$id.'')->with('msgerror','File type not allowed');
				}
			}
			if($wall) {
				$extension =$wall->getClientOriginalExtension();

				$notAllowed = array("exe","php","asp","pl","bat","js","jsp","sh","doc","docx","xls","xlsx");

				$destinationPath = public_path()."/upload/pornstar/";

				$filename = "Pornstar_Wall_Poster_".str_slug($_POST["title_name"],'_').".".$extension;

				if(!in_array($extension,$notAllowed)) {
					$wall->move($destinationPath, $filename);
					$addpornstar =PornStarModel::where('id','=',$id)->update(array('wall_poster'=>$filename));
				} else {
					return redirect('admincp/edit-pornstar/'.$id.'')->with('msgerror','File type not allowed');
				}
			}
			return redirect('admincp/pornstar')->with('msg','Updated Successfully !');
		}
		return redirect('admincp/edit-pornstar/'.$id.'')->with('msgerror','Pornstar name is already exites!');
	}

	public function get_deletepornstar($id){
		$deletepornstar = PornStarModel::find ($id);
		if(!empty($deletepornstar)){
			if(!empty($deletepornstar->poster)){
				if(File::exists(public_path()."/upload/pornstar/".$deletepornstar->poster)){
					unlink(public_path()."/upload/pornstar/".$deletepornstar->poster);
				}
			}
			if(!empty($deletepornstar->wall_poster)){
				if(File::exists(public_path()."/upload/pornstar/".$deletepornstar->wall_poster)){
					unlink(public_path()."/upload/pornstar/".$deletepornstar->wall_poster);
				}
			}
			if($deletepornstar->delete()) {
				return redirect('admincp/pornstar')->with('msg','Delete successfully !');
			}
			return redirect('admincp/pornstar')->with('msgerror','Delete not complete !');
		}

	}

	public function deletePornStarsIds(Request $request){
		$ids = $request->get('ids');
		$count = count($ids);

		foreach ($ids as $key => $id) {
			PornStarModel::removeById($id);
			if($key == $count - 1) {
				return response()->json(['status' => 1]);
			}
		}

		return response()->json(['status' => 0]);
	}

	public function get_addpornstar(){
		return  view('admincp.pornstar.add');
	}

	public function post_addpornstar(Request $get){
		$rules= [
			'title_name'  => 'required|min:5',
			'poster'      => 'required|image|mimes:jpeg,png,jpg',
			'wall_poster' => 'required|image|mimes:jpeg,png,jpg',
			'hair_color' => 'alpha',
			'eye_color' => 'alpha'
		];
		$validator = Validator::make($get->all(), $rules);
		if($validator->fails()){
			return back()->withErrors($validator)->withInput();
		}
		$addpornstar = new PornStarModel();
		$addpornstar->title_name= $_POST["title_name"];
		$addpornstar->post_name= str_slug($_POST["title_name"],"-");
		$addpornstar->description= $_POST["description"];
		// $addpornstar->tag= $_POST["tag"];
		$addpornstar->gender=$_POST["gender"];
		$addpornstar->age=$_POST['age'];
		$addpornstar->born=$_POST['born'];
		$addpornstar->height=$_POST['height'];
		$addpornstar->ethnicity=$_POST['ethnicity'];
		$addpornstar->hair_color=$_POST['hair_color'];
		$addpornstar->eye_color=$_POST['eye_color'];
		$addpornstar->subscribe_status=isset($_POST["subscribe_status"]) ? 1 : 0 ;
		$addpornstar->status=$_POST["status"];
		//var_dump($addchannel);die;
		$file = Input::File('poster');
		$wall = input::File('wall_poster') ;
		$extension =$file->getClientOriginalExtension();

		$destinationPath = public_path()."/upload/pornstar/";

		$filename = "Pornstar_Poster_".str_slug($_POST["title_name"],'_').".".$extension;
		$file->move($destinationPath, $filename);
		$addpornstar ->poster = $filename;

		$extension =$wall->getClientOriginalExtension();

		$destinationPath = public_path()."/upload/pornstar/";

		$filename = "Pornstar_Wall_Poster_".str_slug($_POST["title_name"],'_').".".$extension;

		$wall->move($destinationPath, $filename);

		$addpornstar ->wall_poster = $filename;

		if($addpornstar->save()){
			return redirect('admincp/pornstar')->with('msg','Add Successfully !');
		}
	}

	public function get_pornstarsubscriber(){
		$pornstarsubscribe = PornStarSubscriberModel::select('pornstar_subscriber.*','pornstar.title_name')->where('pornstar_subscriber.status','=','1')
									->join('pornstar','pornstar.id','=','pornstar_subscriber.channel_Id')
									->orderBy('pornstar_subscriber.id', 'ASC')
									->get();
		if($pornstarsubscribe){
			return view('admincp.pornstar.pornstarsubscribe')->with('pornstarsubscribe',$pornstarsubscribe);
		}
	}

	public function get_pornstar_photo_allbum($id){
		$pornstar=PornStarModel::find($id);
		if($pornstar!=NULL){
			$photo_list= PornStarPhotoModel::where('pornstar_id','=',$id)->get();
			return view('admincp.pornstar.photo_allbum')->with('porn_name',$pornstar->title_name)->with('photo_allbum',$photo_list)->with('porn_id',$id)->with('title_pornstar','Manage an Existing Pornstar')->with('pornstar_photo',$pornstar);
		}else{
			return redirect()->back()->with('msgerror','Request not found');
		}
	}

	public function get_add_photo_allbum($id){
		$pornstar=PornStarModel::find($id);
		return view('admincp.pornstar.add_photo')->with('porn_id',$id)->with('porn_name',$pornstar->title_name)->with('title_pornstar','Manage an Existing Pornstar')->with('pornstar_photo',$pornstar);
	}

	public function post_add_photo_allbum($id){

	}

	public function auto_upload_allbum(){
		$upload_folder = public_path()."/upload/pornstar/";
		if (!is_dir($upload_folder)) {
			$upload_folder = mkdir(public_path() . "/upload/pornstar/", 0777, true);
			$upload_folder = public_path() . "/upload/pornstar/";
		}

		if(isset($_FILES["myfile"])) {
			$ret = array();
			$porn_id=$_POST['porn_id'];
			$error =$_FILES["myfile"]["error"];

			if(!is_array($_FILES["myfile"]["name"])) {//single file
				$file_info=$_FILES["myfile"]["type"];
				$extend= explode("/", $file_info);
				$get_extend= end($extend);
				$fileName ="".mt_rand()."_".$porn_id.".".$get_extend."";
				move_uploaded_file($_FILES["myfile"]["tmp_name"],$upload_folder.$fileName);
				$save_file= new PornStarPhotoModel();
				$save_file->pornstar_id=$porn_id;
				$save_file->photo=$fileName;
				$save_file->save();
				$ret[]= $fileName;
			} else {
				$fileCount = count($_FILES["myfile"]["name"]);
				for($i=0; $i < $fileCount; $i++)
				{
					//$fileName = $_FILES["myfile"]["name"][$i];
					$file_info=$_FILES["myfile"]["type"][$i];
					$extend= explode("/", $file_info);
					$get_extend= end($extend);
					$fileName ="".mt_rand()."_".$porn_id.".".$get_extend."";
					$upload_video= move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$upload_folder.$fileName);
					$save_file= new PornStarPhotoModel();
					$save_file->pornstar_id=$porn_id;
					$save_file->photo=$fileName;
					$save_file->save();
					$ret[]= $fileName;
				}

			}
		}
	}

	public function get_photo_delete($id){
		$photo= PornStarPhotoModel::find($id);
		$upload_folder =public_path()."/upload/pornstar/".$photo->photo."";
		if($photo!=NULL && file_exists($upload_folder)==true){
			unlink($upload_folder);
			$delete =PornStarPhotoModel::find($id)->delete();
			if($delete){
				return redirect()->back()->with('msg','Delete successfully!');
			}
		}
		return redirect()->back()->with('msgerror','Request not found !');
	}
}
