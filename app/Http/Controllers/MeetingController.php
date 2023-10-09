<?php

namespace App\Http\Controllers;

use App\Models\MeetingEntry;
use App\Models\UserMeeting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MeetingController extends Controller
{
    public function meetingUser(){
        return view('createMeeing');
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
            }else{
                if(!Auth::User()){
                    $random_user = rand(111111, 999999);
                    $event = generateRamdomString(5);                    
                    $this->createEntry($meeting->user_id, $random_user, $meeting->url, $event, $meeting->channel);
                    $channel = $meeting->channel;
                    //$event = $event;
                }else{
                    $event = generateRamdomString(5);
                    $this->createEntry($meeting->user_id, Auth::User()->id, $meeting->url, $event, $meeting->channel);
                    $channel = $meeting->channel;
                    //$event = $event;
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
}
