<?php

namespace App\Http\Controllers;

use App\Events\sendNotification;
use App\Models\MeetingEntry;
use App\Models\UserMeeting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MeetingController extends Controller
{
    public function meetingUser(){
        $data = Auth::User()->with('getMeetings')->get();
        return view('createMeeing', get_defined_vars());
    }
    public function createMeeting(){
        $meeting = Auth::User()->getUserMeetingInfo()->first() ?? null;
        if(!isset($meeting->id)){
            $name = 'agora' . rand(1111, 9999);
            $meetingData = cretaAgoraProject($name);
            if(isset($meetingData->project->id)){
                $meeting = new UserMeeting();
                $meeting->user_id = Auth::User()->id;
                $meeting->app_id = $meetingData->project->vendor_key;
                $meeting->appCertificate = $meetingData->project->sign_key;
                $meeting->channel = $meetingData->project->name;
                $meeting->uid = rand(11111, 99999);
                $meeting->save();
            }else{
                echo "Project not created";
            }
        }
        $meeting = Auth::User()->getUserMeetingInfo()->first();
        $token = createToken($meeting->app_id, $meeting->appCertificate, $meeting->channel);
        $meeting->token = $token;
        $meeting->url = generateRamdomString();
        $meeting->event = generateRamdomString(5);
        $meeting->save();
        if(Auth::User()->id == $meeting->user_id){
            Session::put('meeting', $meeting->url);
        }
        return redirect('joinMeeting/'. $meeting->url);
    }
    public function joinMeeting($url =''){
        $meeting = UserMeeting::where('url', $url)->first();
        if(isset($meeting->id)){
            $meeting->app_id = trim($meeting->app_id);
            $meeting->appCertificate = trim($meeting->appCertificate);
            $meeting->channel = trim($meeting->channel);
            $meeting->token = trim($meeting->token);

            if(Auth::User() && Auth::User()->id == $meeting->user_id){
                $channel = $meeting->channel;
                $event = $meeting->event;
                $users = User::where('id', '=', Auth::User()->id)->get();
            }else{
                if(!Auth::User()){
                    $random_user = rand(111111, 999999);
                    $event = generateRamdomString(5);      
                    Session::put('random_user', $random_user);    
                    $this->createEntry($meeting->user_id, $random_user, $meeting->url, $event, $meeting->channel);
                    $channel = $meeting->channel;
                }else{
                    $event = generateRamdomString(5);
                    $this->createEntry($meeting->user_id, Auth::User()->id, $meeting->url, $event, $meeting->channel);
                    $channel = $meeting->channel;
                    Session::put('random_user', Auth::User()->id);  
                }
            }
            return view('joinUser', get_defined_vars());
        }else{

        }
    }


    public function createEntry($user_id, $random_user, $url, $event, $channel){
        $entry = new MeetingEntry();
        $entry->user_id = $user_id;
        $entry->random_user = $random_user;
        $entry->url = $url;
        $entry->status = 0;
        $entry->channel = $channel;
        $entry->event = $event;

        $entry->save();
    }

    public function saveUserName(Request $request){
        $saveName = MeetingEntry::where(['random_user' => $request->random, 'url' => $request->url])->first();
        if($saveName->status == 3){

        }else{
            $saveName->name = $request->name;
            $saveName->status = 1;
            $saveName->save();

            $meeting = UserMeeting::where('url', $request->url);
            $data= ['random_user' => $request->random, 'title' => $saveName->name . 'wants to enter in the meeting'];
            event(new sendNotification($data, $meeting->channel, $meeting->event));
        }
    }
    public function meetingApprove(Request $request){
        $saveName = MeetingEntry::where(['random_user' => $request->random, 'url' => $request->url])->first();
        $saveName->status = $request->type;
        if($request->type == 2){
            $saveName->created_at = date("Y-m-d h:i:s");
            $saveName->updated_at = date("Y-m-d h:i:s");

        }
        $saveName->save();
        $data= ['status' => $request->type];
        event(new sendNotification($data, $saveName->channel, $saveName->event));
    }
    public function callRecordTime(Request $request){
        $saveName = MeetingEntry::where(['random_user' => $request->random, 'url' => $request->url])->first();
        $saveName->updated_at = date("Y-m-d h:i:s");

        $saveName->save(); 
        return response()->json(['status' => 'success', 'msg' => 'time added']);
    }
}
