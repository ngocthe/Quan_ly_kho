<?php

namespace App\Models;

use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    protected $guarded  = [];

    public function reference()
    {
        return $this->belongsTo('App\Models\Partner');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CompanyScope());
    }
}
