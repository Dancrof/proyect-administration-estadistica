<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ["url_address", "name", "user_id", "type_id"];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function type(){
        return $this->belongsTo(Type::class);
    }
}
