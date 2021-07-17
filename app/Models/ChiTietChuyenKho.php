<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietChuyenKho extends Model
{
    protected $guarded  = [];
    public function chuyenKho()
    {
        return $this->belongsTo('App\Models\ChuyenKho', 'chuyen_kho_id');
    }

    public function pheLieu()
    {
        return $this->belongsTo('App\Models\PheLieu', 'phe_lieu_id');
    }

}
