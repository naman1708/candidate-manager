<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','portal_name','username','password','phone_number','security_question','security_answer'
    ];

    public function manager(){
        return $this->belongsTo(User::class,'user_id');
    }
}
