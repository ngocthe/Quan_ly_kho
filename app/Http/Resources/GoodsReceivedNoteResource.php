<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GoodsReceivedNoteResource extends JsonResource
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
            'id' => $this->id,
            'date' => $this->date,
            'partner_id' => $this->partner_id,
            'partner' => $this->partner,
            'unit' => $this->unit,
            'document_number' => $this->document_number,
            'exchange_rate' => $this->exchange_rate,
            'type' => $this->type,
            'tax' => $this->tax,
            'products' => $this->products->map(function ($product) {
                return [
                    'id' => $product->pivot->product_id,
                    'product_id' => $product->pivot->product_id,
                    'name' => $product->name,
                    'price' => $product->pivot->price,
                    'plastic_id' => $product->plastic_id,
                    'quantity' => $product->pivot->quantity,
                    'cost_price' => $product->pivot->cost_price,
                ];
            }),
        ];
    }
}
