<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Torelance extends Model
{
    protected $fillable = ['days','control_number'];

    public function logs(){
        return $this->morphMany(Activity::class,'loggable','subject_type','subject_id');
    }

}
