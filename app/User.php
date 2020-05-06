<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use Sluggable;


    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['fname','lname'],
                'separator' => '_',
                'onUpdate' => true
            ]
        ];
    }
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname','lname', 'email', 'password','role_id','status_id','gender_id','phone','slug'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function getNameAttribute(){
        return ucfirst($this->fname)." ".ucfirst($this->lname);
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }

    public function gender(){
        return $this->belongsTo(Gender::class);
    }

    public function logs(){
        return $this->hasMany(Activity::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function permissions(){
        return $this->hasMany(Permission::class);
    }

    public function scopeGranted($query,$name){     //Check permission
        if ($this->permissions()->whereName($name)->first() || $this->role->name=='admin'){
            return true;
        }else{
            return false;
        }
    }
}
