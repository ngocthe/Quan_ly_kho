<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class ActionGroup extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function actions()
    {
        return $this->hasMany('App\Models\System\Action');
    }
}
