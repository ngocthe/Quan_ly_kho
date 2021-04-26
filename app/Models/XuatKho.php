<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XuatKho extends Model
{
    protected $guarded = [];
    public function kho()
    {
        return $this->belongsTo('App\Models\Kho', 'kho_id');
    }

    public function doiTac()
    {
        return $this->belongsTo('App\Models\DoiTac', 'doi_tac_id');
    }
    public function xe()
    {
        return $this->belongsTo('App\Models\Xe', 'xe_id');
    }
    public function chitiets()
    {
        return $this->hasMany('App\Models\ChiTietXuatKho', 'xuat_kho_id')->with('pheLieu');
    }
}
