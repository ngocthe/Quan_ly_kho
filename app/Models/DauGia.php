<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class DauGia extends Model
{
    protected $guarded = [];

    protected $casts=['hinh_anhs'=>'array'];
    protected $appends = ['trang_thai','cao_nhat'];

    public function chitiets()
    {
        return $this->hasMany('App\Models\KhachHangDauGia', 'dau_gia_id');
    }
    public function getTrangThaiAttribute(){
        $now= Carbon::now();
      if($now >=$this->attributes['ket_thuc']){
            return 'da_ket_thuc';
      }
      if($now<$this->attributes['ket_thuc'] && $now>=$this->attributes['bat_dau']){
        return 'dang_dien_ra';
    }
      return 'chua_dien_ra';
    }
    public function getCaoNhatAttribute(){
      $now= Carbon::now();
      $kh=KhachHangDauGia::where('dau_gia_id',$this->attributes['id'])->orderBy('gia','asc')->first();
      if(isset($kh)){
            return $kh->gia;
      }
    
    return 0;
  }

}
