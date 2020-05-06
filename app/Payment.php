<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    protected $fillable = ['control_number','amount','slip','depositor','user_id','created_at'];

    public function logs(){
        return $this->morphMany(Activity::class,'loggable','subject_type','subject_id');
    }


    public function controlNumber(){
        return $this->belongsTo(ControlNumber::class,'control_number','number');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function transfer(){
        return $this->hasMany(Transfer::class);
    }

    public function getHasTransferAttribute(){
        return $this->transfer()->count()>0?true:false;
    }

    public function getMyTransferAttribute(){
        return $this->transfer()->first();
    }

    public function getMyTransferInfoAttribute(){
        $transfer =  $this->transfer()->first();
        $info = "Amount of ".number_format($transfer->amount)." was transfered from suspence with control Number (".$transfer->suspence->control.")";
        return $info;
    }

    public function getDepositorAttribute($depositor){
        return ucfirst(strtolower($depositor));
    }
}
