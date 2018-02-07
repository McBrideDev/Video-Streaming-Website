<?php

namespace App\Http\Controllers\admincp;
use App\Http\Controllers\Controller;
use App\Models\ChannelModel;
use App\Models\ChanneSubscriberModel;
use App\Models\MemberModel;
use App\Models\EmailSettingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Helper\AppHelper;

class ChannelController extends Controller
{

    public function get_channel(){
            $channel = ChannelModel::orderBy('id', 'DESC')
                                    ->get();
            if($channel){
                return view('admincp.channel.index')->with('channel',$channel);

            }
    }
    public function get_editchannel($id){
        $editchannel = ChannelModel::find($id);

        if($editchannel)
        {
            return view('admincp.channel.edit')->with('editchannel',$editchannel);
        }
    }

    public function post_editchannel(Request $request,$id){
        $rules = [
            'title_name' => 'required|min:5',
            // 'poster' => 'required|image|mimes:jpeg,png,jpg'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $addchannel =ChannelModel::where('id','=',$id)->update(array(
                'title_name' => $request->title_name,
                'post_name'  => str_slug($request->title_name,"-"),
                'description' => $request->description,
                'subscribe_status' => isset($request->subscribe_status) ? 1: 0 ,
                'status' => $request->status
        ));

        $file = Input::File('poster');
        if($file){
                $extension =$file->getClientOriginalExtension();

                $notAllowed = array("exe","php","asp","pl","bat","js","jsp","sh","doc","docx","xls","xlsx");

                $destinationPath = public_path()."/upload/channel/";

                $filename = "Channel_Poster_".str_slug($_POST["title_name"],'_').".".$extension;

                if(!in_array($extension,$notAllowed))
                {

                     $file->move($destinationPath, $filename);
                     ChannelModel::where('id','=',$id)->update(array('poster'=>$filename));

                }else{
                     return redirect('admincp/edit-channel/'.$id.'')->with('msg','File type not allowed!');
                }
        }
        if($addchannel){
            return redirect('admincp/channel')->with('msg','Updated Successfully !');
        }
        return redirect('admincp/edit-channel/'.$id.'')->with('msgerror','Update field !');
    }

    public function get_deletechannel($id){
        $deletechannel = ChannelModel::find($id);
        if(!empty($deletechannel)){
            if(!empty($deletechannel->poster)){
                if(File::exists(public_path()."/upload/channel/".$deletechannel->poster)){
                    unlink(public_path()."/upload/channel/".$deletechannel->poster);
                }
            }
            if($deletechannel->delete()){
                return redirect('admincp/channel')->with('msg','Delete Successfully !');
            }
            return redirect('admincp/channel')->with('msgerro','Can not delete this channel');
        }
        return redirect('admincp/channel')->with('msgerro', 'Channel not found');

    }

    public function removeIds(Request $request){
        $ids = $request->get('ids');
        $count = count($ids);

        foreach ($ids as $key => $id) {
            ChannelModel::removeById($id);
            if($key == $count - 1) {
                return response()->json(['status' => 1]);
            }
        }

        return response()->json(['status' => 0]);

    }

    public function get_addchannel(Request $request){
       return  view('admincp.channel.add');
    }
    public function post_addchannel(Request $get ){
        $rules = [
            'title_name' => 'required|min:5',
            'poster' => 'required|image|mimes:jpeg,png,jpg'
        ];
        $validator = Validator::make($get->all(), $rules);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $addchannel = new ChannelModel();

        $file = Input::file('poster');

        $extension =$file->getClientOriginalExtension();

        $destinationPath = public_path()."/upload/channel/";

        $filename = "Channel_Poster_".str_slug($_POST["title_name"],'_').".".$extension;

        $file->move($destinationPath, $filename);

        $addchannel->poster = $filename;
        $addchannel->title_name= $get->title_name;
        $addchannel->post_name= str_slug($get->title_name,"-");
        $addchannel->description= $get->description;
        $addchannel->subscribe_status=isset($get->subscribe_status) ? ChannelModel::SUBSCRIBE_ACTIVE : ChannelModel::SUBSCRIBE_INACTIVE ;
        $addchannel->status=$get->status;

        if($addchannel->save()){
          return redirect('admincp/channel')->with('msg','Add Channel Successfully !');
        }
        return redirect('admincp/add-channel')->with('msgerro','Add Channel Field !');
    }

    public function get_channelsubscriber(){
         $channelsubscribe = ChanneSubscriberModel::select('channel_subscriber.*','channel.title_name')->where('channel_subscriber.status','=','1')
                                    ->Join('channel','channel.id','=','channel_subscriber.channel_Id')
                                    ->orderBy('channel_subscriber.id', 'ASC')
                                    ->get();
        if($channelsubscribe){
                return view('admincp.channel.channelsubscribe')->with('channelsubscribe',$channelsubscribe);
            }

    }

    public function get_approve_register($id){
        $checkid=ChannelModel::find($id);
        $get_email_temp=EmailSettingModel::get_channel_email_register();
        $getoption = AppHelper::getSiteConfig();
        $get_member=MemberModel::where('user_id','=',$checkid->user_id)->first();
        if($checkid!=NULL){
            $update=$checkid->update(array('status'=> ChannelModel::APPROVED));
            $update_member=MemberModel::where('user_id','=',$checkid->user_id)->update(array('is_channel_member'=> ChannelModel::IS_CHANNEL_MEMBER));
            if($update && $update_member){
                $sendmail = Mail::send('admincp.mail.'.$get_email_temp->name_slug.'',array(
                        'firstname'=>$get_member->firstname,
                        'lastname'=>$get_member->lastname,
                        'site_name'=>$getoption->site_name,
                        'site_phone'=>$getoption->site_phone,
                        'site_email'=>$getoption->site_email,
                        'channel_name'=>$checkid->title_name
                        ),function($message) use($get_member){
                        $message->to($get_member->email)->subject('Adult Streaming Website Administrator Approve Channel');
                    });
                    if($sendmail){
                        return 1;
                    }
            }
        }else{
            return 0;
        }
    }


    public function get_member_list_subscribe($id){
        $listID=explode(',', $id);
        $member= MemberModel::whereIn('id',$listID)->get();
        if(count($member)>0){
            $html="";

            foreach ($member as $result) {
               $html.="<tr>";
               $html.="<td>".$result->username."</td>";
               $html.="<td>".$result->email."</td>";
               $html.="</tr>";
            }
            return $html;
        }else{
            return 0;
        }

    }

}
