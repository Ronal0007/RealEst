<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plot extends Model
{
    use SoftDeletes;
    use Sluggable;


    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['block.locality.project.name','block.locality.name','block.code','number'],
                'separator' => '_',
                'onUpdate' => true
            ]
        ];
    }
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'number','size','surveyNumber','registeredNumber','block_id','plotuse_id','status_id','slug','user_id'
    ];

    public function logs(){
        return $this->morphMany(Activity::class,'loggable','subject_type','subject_id');
    }

    public function block(){
        return $this->belongsTo(Block::class);
    }

    public function plotuse(){
        return $this->belongsTo(Plotuse::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function controlNumber(){
        return $this->hasOne(ControlNumber::class);
    }

    public function payments(){
        return $this->hasManyThrough(Payment::class,ControlNumber::class,'plot_id','control_number','id','number');
    }

    public function getHasPaymentsAttribute(){
        return $this->payments()->count()>0?true:false;
    }

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }
}
