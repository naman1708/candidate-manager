<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleInterview extends Model
{
    use HasFactory;
    // protected $with = ['candidate','candidateRole'];
    protected $fillable = [
        'candidate_id','interview_date','interview_time','interview_status','reason'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }

    public function getInterviewDateAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-M-Y');
    }

}
