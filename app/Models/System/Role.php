<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtolower(str_replace(' ', '', $value));
    }
    public function users()
    {
        return $this->hasMany('App\Models\System\User');
    }
    public function actions()
    {
        return $this->belongsToMany('App\Models\System\Action');
    }
}
