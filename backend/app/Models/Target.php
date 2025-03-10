<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    protected $table = 'targets';
    protected $fillable = ['user_id', 'name', 'target_amount','saved_amount','deadline'];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}