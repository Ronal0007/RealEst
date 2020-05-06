<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plotuse extends Model
{
    protected $fillable = ['name'];

    public function plots(){
        return $this->hasMany(Plot::class);
    }
}
