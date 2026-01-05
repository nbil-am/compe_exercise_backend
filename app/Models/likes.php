<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class likes extends Model
{
    protected $fillable = ['post_id'];
    public function Posts() {
        $this->belongsTo(Posts::class,'post_id','id');
    }
    public function user() {
        $this->belongsTo(User::class,'user_id','id');
    }
}
