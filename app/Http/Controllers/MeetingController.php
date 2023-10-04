<?php

namespace App\Http\Controllers;

use App\Models\UserMeeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    public function meetingUser(){
        return view('createMeeing');
    }
    public function createMeeting(){
        $meeting = Auth::User()->getUserMeetingInfo()->first();
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
        $meeting->save();
        dd($token);
        //prx($token);


    }
}
