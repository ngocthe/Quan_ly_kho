<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietNhapKho extends Model
{
    protected $guarded = [];
    public function pheLieu()
    {
        return $this->belongsTo('App\Models\PheLieu', 'phe_lieu_id');
    }
    public function nhapKho()
    {
        return $this->belongsTo('App\Models\NhapKho', 'nhap_kho_id');
    }
}
