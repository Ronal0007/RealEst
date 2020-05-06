<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locality extends Model
{
    use SoftDeletes;
    use Sluggable;


    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['project.name','name'],
                'separator' => '_',
                'onUpdate' => true
            ]
        ];
    }
    protected $dates = ['deleted_at'];
    protected $fillable = ['name','project_id','slug','user_id'];

    public function logs(){
        return $this->morphMany(Activity::class,'loggable','subject_type','subject_id');
    }


    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function blocks(){
        return $this->hasMany(Block::class);
    }

    public function plots(){
        return $this->hasManyThrough(Plot::class,Block::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
