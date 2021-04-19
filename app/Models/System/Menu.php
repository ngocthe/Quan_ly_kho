<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function children()
    {
        return $this->hasMany('App\Models\System\Menu');
    }
    public function parent()
    {
        return $this->belongsTo('App\Models\System\Menu', 'menu_id');
    }
}
