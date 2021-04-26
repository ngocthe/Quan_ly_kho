<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhanLoai extends Model
{
    protected $guarded = [];
    public function chitiets()
    {
        return $this->hasMany('App\Models\ChiTietPhanLoai', 'phan_loai_id');
    }

    public function kho()
    {
        return $this->belongsTo('App\Models\Kho', 'kho_id');
    }

    public function khachHang()
    {
        return $this->belongsTo('App\Models\KhachHang', 'khach_hang_id');
    }

    public function pheLieu()
    {
        return $this->belongsTo('App\Models\PheLieu', 'phe_lieu_id');
    }
}
