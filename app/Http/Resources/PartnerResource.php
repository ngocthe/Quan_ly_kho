<?php

namespace App\Http\Resources;

use App\Models\Debt;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class PartnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'active'=> $this->active,
            'address'=>  $this->address,
            'code'=>  $this->code,
            'company_id'=> $this->company_id,
            'created_at'=>  $this->created_at,
            'debt'=>  Debt::where('reference_id',$this->id)->sum(DB::raw('amount_owed *exchange_rate - amount_payment')),
            'id'=>  $this->id,
            'main_product'=>  $this->main_product,
            'name'=>  $this->name,
            'phone_number'=>  $this->phone_number,
            'pic'=>  $this->pic,
            'tax_code'=>  $this->tax_code,
            'type'=> $this->type,
            'updated_at'=>  $this->updated_at,
            'vat'=> $this->vat,
        ];
    }
}
