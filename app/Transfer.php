<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = [
        'suspence_id','control_number','amount','payment_id','user_id'
    ];

    public function logs(){
        return $this->morphMany(Activity::class,'loggable','subject_type','subject_id');
    }

    public function suspence(){
        return $this->belongsTo(Suspence::class)->withTrashed();
    }

    public function control(){
        return $this->belongsTo(ControlNumber::class,'control_number','number');
    }

    public function payment(){
        return $this->belongsTo(Payment::class);
    }
}
