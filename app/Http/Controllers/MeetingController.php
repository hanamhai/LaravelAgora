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
        if(!isset($meeting->id) != null){
            $name = 'agora' . rand(1111, 9999);
            $meetingData = cretaAgoraProject($name);
            if(!isset($meetingData->project->id)){
                $meeting = new UserMeeting();
                $meeting->user_id = Auth::user()->id;
                $meeting->user_id = Auth::user()->id;
                $meeting->user_id = Auth::user()->id;
                $meeting->user_id = Auth::user()->id;
            }else{
                echo "Project not created";
            }
        }
    }
}
