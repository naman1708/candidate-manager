<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateRoles extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_role','status',
    ];
    
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}
