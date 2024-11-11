<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = ['Description', "user_id", "state_id"];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function state(){
        return $this->belongsTo(State::class);
    }
}
