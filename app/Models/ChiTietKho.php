<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietKho extends Model
{
    protected $guarded = [];
    public function pheLieu()
    {
        return $this->belongsTo('App\Models\PheLieu', 'phe_lieu_id');
    }
}
