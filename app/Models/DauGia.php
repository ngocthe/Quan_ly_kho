<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DauGia extends Model
{
    protected $guarded = [];

    protected $casts=['hinh_anhs'=>'array'];
}
