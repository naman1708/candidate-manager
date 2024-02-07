<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;
    protected $fillable = [
        'candidate_name','candidate_role_id','date','source','date','experience','contact','email','status','salary','expectation','upload_resume','contact_by','interview_status_tag','comment','user_id','superadmin_instruction'
    ];

    public function candidateRole()
    {
        return $this->belongsTo(CandidateRoles::class, 'candidate_role_id','id');
    }

    public function scheduleInterview()
    {
        return $this->hasOne(ScheduleInterview::class, 'candidate_id', 'id');
    }

    public function createby()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
