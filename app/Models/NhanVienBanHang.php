<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhanVienBanHang extends Model
{
    protected $guarded = [];
    public function kho()
    {
        return $this->belongsTo('App\Models\Kho', 'kho_id');
    }

}
