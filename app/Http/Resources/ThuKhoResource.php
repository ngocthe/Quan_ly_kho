<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThuKhoResource extends JsonResource
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
            'cmnd' => $this->cmnd,
            'email' => $this->email,
            'ten' => $this->ten,

            'id' => $this->id,
            'kho_id' => $this->kho_id,
            'khos'=>$this->khos,
            'sdt'=>$this->sdt,
            'user_id'=>$this->user_id,
            'kho_ids' => $this->khos->pluck('id')
        ];
    }
}
