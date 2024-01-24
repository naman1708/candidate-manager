<?php

namespace App\Http\Controllers;

use App\Models\ScheduleInterview;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ScheduleInterviewController extends Controller
{

    public function scheduleInterview(Request $request){
        $request->validate([
            'candidate_id' => 'required',
            'interview_date' => 'required',
            'interview_time' => 'required',
            'interview_status' => 'required',
            'reason' => 'required',
        ]);
        DB::beginTransaction();
        try{

           $data = [
            'candidate_id' => $request->candidate_id,
            'interview_date' => $request->interview_date,
            'interview_time' => $request->interview_time,
            'interview_status' => $request->interview_status,
            'reason' => $request->reason,
           ];

           $scheduleInterview = ScheduleInterview::where('candidate_id', $request->candidate_id)->first();

           if ($scheduleInterview) {
               $scheduleInterview->update($data);
           } else {
               $data['candidate_id'] = $request->candidate_id;
               ScheduleInterview::create($data);
           }

        }catch(Exception $e){
            DB::rollBack();
            return Redirect::back()->with('status',$e->getMessage());
        }
        DB::commit();
            return Redirect::back()->with('status',"Interview schedule successfully done!");

    }

}
