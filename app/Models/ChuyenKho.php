<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChuyenKho extends Model
{
    protected $guarded  = [];
    public function chitiets()
    {
        return $this->hasMany('App\Models\ChiTietChuyenKho', 'chuyen_kho_id')->with('pheLieu');
    }

    public function tuKho()
    {
        return $this->belongsTo('App\Models\Kho', 'tu_kho_id');
    }

    public function denKho()
    {
        return $this->belongsTo('App\Models\Kho', 'den_kho_id');
    }
    public function nguoiTao()
    {
        return $this->belongsTo('App\Models\System\User', 'nguoi_tao_phieu');
    }
}
