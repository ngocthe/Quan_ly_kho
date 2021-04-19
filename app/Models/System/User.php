<?php

namespace App\Models\System;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */


    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */


    public function role()
    {
        return $this->belongsTo('App\Models\System\Role');
    }
    public function hasPermission($action)
    {
        return (bool) $this->role->actions()->whereReference($action)->count();
    }
}
