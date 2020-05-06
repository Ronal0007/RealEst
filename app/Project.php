<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    use Sluggable;


    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'separator' => '_',
                'onUpdate' => true
            ]
        ];
    }
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    protected $dates = ['deleted_at'];
    protected $fillable = ['name','acqfactor','dmcfactor','status_id','slug','user_id'];

    public function logs(){
        return $this->morphMany(Activity::class,'loggable','subject_type','subject_id');
    }


    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function localities(){
        return $this->hasMany(Locality::class);
    }

    public function projectPrices(){
        return $this->hasMany(ProjectPrice::class);
    }

    public function scopeAmount($query,$plotuse){
        return $this->projectPrices()->where('plotuse_id',$plotuse)->first()->amount;
    }

    public function blocks(){
        return $this->hasManyThrough(Block::class,Locality::class);
    }
    public function plots(){
        return $this->hasManyDeep(Plot::class,[Locality::class,Block::class]);
    }

    public function controlNumbers(){
        return $this->hasManyDeep(ControlNumber::class,[Locality::class,Block::class,Plot::class]);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getCurrentAmountAttribute(){
        $amount = 0;
        foreach ($plots = $this->plots()->where('status_id',2)->get() as $plot) {
            $amount+=$plot->payments()->sum('amount');
        }

        return $amount;
    }

    public function getExpectedAmountAttribute(){
        $amount = 0;
        foreach ($plots = $this->plots()->where('status_id',2)->get() as $plot) {
            $amount+=$plot->controlNumber->totalRequiredAcq;
        }

        return $amount;
    }



    public function getSoldPlotsAttribute(){
        return $this->plots()->where("status_id",2)->count();
    }
}
