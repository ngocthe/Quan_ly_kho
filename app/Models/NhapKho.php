<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhapKho extends Model
{
    protected $guarded = [];

    public function kho()
    {
        return $this->belongsTo('App\Models\Kho', 'kho_id');
    }

    public function khachHang()
    {
        return $this->belongsTo('App\Models\KhachHang', 'khach_hang_id');
    }
    public function xe()
    {
        return $this->belongsTo('App\Models\Xe', 'xe_id');
    }
    public function chitiets()
    {
        return $this->hasMany('App\Models\ChiTietNhapKho', 'nhap_kho_id')->with('pheLieu');
    }
}
