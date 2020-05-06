<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectPrice extends Model
{
    protected $fillable = ['amount','rate','plotuse_id','project_id'];

    public function plotuse(){
        return $this->belongsTo(Plotuse::class);
    }
}
