<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activity_log';
    protected $guarded = [];
    protected $dates = ['logged_at'];

    public function subject(){
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopeUserLogs($query){
        return $query->where('name','Authentication');
    }

    public function scopeOtherLogs($query){
        return $query->where('name','!=','Authentication');
    }

}
