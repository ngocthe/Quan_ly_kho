<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function menu()
    {
        return $this->belongsTo('App\Models\System\Menu');
    }
    public function group()
    {
        return $this->belongsTo('App\Models\System\ActionGroup', 'action_group_id');
    }
}
