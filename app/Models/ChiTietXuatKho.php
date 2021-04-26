<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietXuatKho extends Model
{
    
    protected $guarded = [];
    public function xuatKho()
    {
        return $this->belongsTo('App\Models\XuatKho', 'xuat_kho_id');
    }

    public function pheLieu()
    {
        return $this->belongsTo('App\Models\PheLieu', 'phe_lieu_id');
    }
}
