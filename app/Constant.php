<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Constant extends Model
{
    use SoftDeletes;
    protected $dates = ['from','to'];
    protected $fillable = [
        'year',
        'advanceFactor',
        'ramani',
        'hati',
        'usajiriFactor',
        'ushuruSubstraction',
        'ushuruFactor',
        'ushuruAddition'
    ];

    public function controlNumbers(){
        return $this->hasMany(ControlNumber::class);
    }

    public function logs(){
        return $this->morphMany(Activity::class,'loggable','subject_type','subject_id');
    }
}
