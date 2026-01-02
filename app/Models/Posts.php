<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $fillable = ['content'];
    
    public function User() {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function likes() {
        return $this->hasMany(likes::class);
    }
    public function comments() {
        return $this->hasMany(Comments::class);
    }
}
