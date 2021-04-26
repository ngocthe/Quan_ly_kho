<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietPhanLoai extends Model
{
    protected $guarded = [];

    
    public function kho()
    {
        return $this->belongsTo('App\Models\Kho', 'kho_id');
    }

    public function phanLoai()
    {
        return $this->belongsTo('App\Models\PhanLoai', 'phan_loai_id');
    }

    public function pheLieu()
    {
        return $this->belongsTo('App\Models\PheLieu', 'phe_lieu_id');
    }
}
