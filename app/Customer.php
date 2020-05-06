<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['fname','lname','phone','gender_id','user_id'];

    public function getNameAttribute(){
        return ucfirst(strtolower($this->fname))." ".ucfirst(strtolower($this->lname));
    }

    public function controlNumbers(){
        return $this->hasMany(ControlNumber::class);
    }

    public function gender(){
        return $this->belongsTo(Gender::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
