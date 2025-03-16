<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = ['user_id', 'action', 'details'];
    protected $with = ['user'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
