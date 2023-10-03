<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;
    protected $fillable = [
        'candidate_name','candidate_role_id','date','source','date','experience','contact','email','status','salary','expectation','upload_resume','contact_by'
    ];

    public function candidateRole()
    {
        return $this->belongsTo(CandidateRoles::class, 'candidate_role_id','id');
    }
}
