<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kho extends Model
{
    protected $guarded = [];
    public function thuKho()
    {
        return $this->belongsTo('App\Models\ThuKho', 'thu_kho_id');
    }

    public function nvbh()
    {
        return $this->belongsTo('App\Models\NhanVienBanHang', 'nhan_vien_ban_hang_id');
    }
    public function chitiets()
    {
        return $this->hasMany('App\Models\ChiTietKho', 'kho_id')->with('pheLieu');
    }
}
