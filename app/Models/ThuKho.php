<?php

namespace App\Models;
use App\Models\Kho;
use Illuminate\Database\Eloquent\Model;

class ThuKho extends Model
{
    protected $guarded = [];
    public function khos()
    {
        return $this->belongsToMany(Kho::class,'thu_kho_khos');
    }
    // public function kho()
    // {
    //     return $this->belongsTo('App\Models\Kho', 'kho_id');
    // }

}
