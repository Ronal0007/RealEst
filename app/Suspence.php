<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suspence extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['customer','amount','plot','control','user_id'];

    public function logs(){
        return $this->morphMany(Activity::class,'loggable','subject_type','subject_id');
    }


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function transfers(){
        return $this->hasMany(Transfer::class);
    }

    public function getRemainAttribute(){
        return $this->amount-($this->transfers()->sum('amount'));
    }
}
