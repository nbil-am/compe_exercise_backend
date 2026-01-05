<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $fillable = ['comment','post_id'];
    protected $hidden = ['post_id'];
    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function post() {
        return $this->belongsTo(Posts::class,'post_id','id');
    }
}
