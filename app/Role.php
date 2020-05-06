<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
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
    protected $fillable = ['name','slug'];
}
