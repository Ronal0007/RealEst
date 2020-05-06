<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model
{
    use SoftDeletes;
    use Sluggable;


    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['locality.project.name','locality.name','code'],
                'separator' => '_',
                'onUpdate' => true
            ]
        ];
    }

    protected $dates = ['deleted_at'];
    protected $fillable = ['code','surveyNumber','locality_id','slug','user_id'];

    public function locality(){
        return $this->belongsTo(Locality::class);
    }

    public function plots(){
        return $this->hasMany(Plot::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function setCode($value){
        return strtoupper($value);
    }

    public function logs(){
        return $this->morphMany(Activity::class,'loggable','subject_type','subject_id');
    }
}
