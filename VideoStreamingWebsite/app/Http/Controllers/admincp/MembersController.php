<?php
namespace App\Http\Controllers\admincp;
use App\Http\Controllers\Controller;
use App\Services\Modules\Modules;
use App\Models\ActivityLogModel;
use App\Models\MemberModel;
use App\Models\VideoModel;
use App\Models\UserSignupModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use App\Helper\VideoHelper;
use Validator;

class MembersController extends Controller
{
	public function get_user(){
		$member = MemberModel::where('username','<>','longpham')
		->where('username','<>','admin')
		->OrderBy('created_at','desc')->get();

		if($member){
		return view('admincp.member.index')->with('member',$member);
		}
	}

	public function get_edituser($id){
		$member_edit = MemberModel::find($id);
		if($member_edit){
			return view('admincp.member.edit')->with('member_edit',$member_edit)->with('title_pornstar','Manage Existing Users');
		}
	}
	public function post_edituser(Request $request, $id){

		$rules = [
			'firstname' =>  'required|min:4|max:32',
			'lastname' => 'required|min:4|max:32'
		];
		$mem = MemberModel::find($id);
		if($mem->email != $request->get('email')) {
			$rules['email'] = 'required|email|unique:members|unique:users';
		}

		$validator = Validator::make($request->all(), $rules);
		if($validator->fails()){
			return back()->withErrors($validator)->withInput();
		}

		$memberadd =  MemberModel::where ('id','=',$id)->update(array(
			'firstname' => $request->firstname,
			'lastname' =>$request->lastname,
			'birthdate' => $request->birthdate,
			'email' =>$request->email,
			'upload_status' => isset($request->upload_status) ? 1 : 0 ,
			'embed_status' => isset($request->embed_status) ? 1 : 0 ,
			'sex' => isset($request->sex) ? 1 : 0,
			'status' => $request->status
			));

		$file = Input::file('photo');
		if($file){
			$extension =$file->getClientOriginalExtension();

			$notAllowed = array("exe","php","asp","pl","bat","js","jsp","sh");

			$destinationPath = public_path()."/upload/member/";

			$name = str_replace(array(',',':','-','_','.'),'',$file->getClientOriginalName());
			$name = trim($name);
			$filename = "member".$_POST["user_id"]."".$name.".".$extension;

			if(!in_array($extension,$notAllowed))
			{
				$file->move($destinationPath, $filename);
				$avatar =  MemberModel::where('id','=',$id)->update(array('avatar' => $filename));
			}
		}

		if($memberadd){
		return redirect('admincp/user');
	}

	}

	public function get_member_video_upload(){

		$get_all= VideoModel::select('video.*','members.firstname','members.lastname')
		->join('members','members.user_id','=','video.user_id')
		->get();
		return view('admincp.member.videolist')->with('video',$get_all);
	}

	public function set_approve($id){
		$update =VideoModel::where('id','=',$id)->update(array('status'=> VideoModel::STATUS_COMPLETED));
		if($update){
			VideoHelper::removeDetailCache($id);
			return '<span class="label label-success pointer">Approved</span>';
		}
	}

	public function set_block($id){
		$update =VideoModel::where('id','=',$id)->update(array('status'=> VideoModel::BLOCKED));
		if($update){
			return '<span class="label label-danger pointer">Blocked</span>';
		}
	}

	public function get_addmember(){
		return view('admincp.member.add_member');
	}

	public function post_addmember(Request $get){
		if($get){
			$rules = [
				'firstname' =>  'required|min:4|max:32',
				'lastname' => 'required|min:4|max:32',
				'username' => 'required|min:4|max:32|unique:members|unique:users',
				'password' => 'required|min:6|max:32|confirmed',
				'password_confirmation' => 'required|min:6|max:32',
				'email' => 'required|email|unique:members|unique:users'
			];
			$validator = Validator::make($get->all(), $rules);
			if($validator->fails()){
				return back()->withErrors($validator)->withInput();
			}
			$firstname= $get->firstname;
			$lastname= $get->lastname;
			$username= $get->username;
			$passwords=$get->password;
			$passwordagain=$get->passwordagain;
			$emails=$get->email;
			$_token=$get->_token;

			$newuser = new UserSignupModel ();
			$newuser->username=$username;
			$newuser->password=md5($passwords);
			$newuser->firstname=$firstname;
			$newuser->lastname=$lastname;
			$newuser->email=$emails;
			$newuser->remember_token=$_token;

			if($newuser->save()){
				\Session::put("UserNew",$newuser);
				$user= \Session::get('UserNew');
				$newmember =new MemberModel();
				$file = Input::file('photo');
				if($file){
					$extension =$file->getClientOriginalExtension();
					$notAllowed = array("exe","php","asp","pl","bat","js","jsp","sh","mp4","mp3","flv","avi");
					$destinationPath = public_path()."/upload/member/";
					$name = str_replace(array(',',':','-','_','.'),'',$file->getClientOriginalName());
					$name = trim($name);
					$filename = "member".$user->id."".$name.".".$extension;
					if(!in_array($extension,$notAllowed))
					{
						$file->move($destinationPath, $filename);
						$newmember->avatar = $filename;
					}
				}
				$newmember->user_id= $user->id;
				$newmember->username=$username;
				$newmember->password=md5($passwords);
				$newmember->email = $emails;
				$newmember->firstname=$firstname;
				$newmember->lastname=$lastname;
				$newmember->status=1;
				if($newmember->save()){
					return redirect('admincp/user')->with('msg','Add member successfully');
				}
			}
		}
	}

	//Block member
	public function set_block_member($id){
		if($id !=NULL){
			$check_member =MemberModel::where('id','=',$id);
			if($check_member->first()!=NULL){
				$update = $check_member->update(array('status'=>3));
				if($update){
					return "<span class='label label-danger'>Block</span>";
				}
			}
		}
	}

	//Approve member
	public function set_approve_member($id){
		if($id !=NULL){
			$check_member =MemberModel::where('id','=',$id);
			if($check_member->first()!=NULL){
				$update =  $check_member->update(array('status'=>1));
				if($update){
					return "<span class='label label-success'>Approve</span>";
				}
			}
		}
	}


	//delete member
	public function get_delete_user($id){
		if($id !=NULL){
			$check=MemberModel::find($id);
			if($check!=NULL){
				$delete_user= UserSignupModel::where('id','=',$check->user_id)->delete();
				if($delete_user){
					$delete = MemberModel::where('id','=',$id)->delete();
					if($delete){
						return redirect('admincp/user')->with('msg','Delete member successfully !');
					}
				}
			}
		}
		return redirect('admincp/user')->with('msgerror','Member Not found !');
	}

	public function get_delete_member_list($listid){
		$memberid=explode(',',$listid);
		for ($i=0; $i <count($memberid) ; $i++) {
			$delete=MemberModel::where('id','=',$memberid[$i])->delete();
		}
		return redirect('admincp/user')->with('msg','Members in list has been delete !');
	}

}

