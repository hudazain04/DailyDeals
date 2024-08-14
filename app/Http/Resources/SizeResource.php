<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SizeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        $product_id = $this->additional['product_id'];
        if(! $this->colors)
        {
            $colors=$this->product_info()->color();
        }
        else
        {
            $colors=$this->colors;
        }
        return [
          'size'=>$this->size,
          'unit'=>$this->unit,
          'price'=>$this->price,
          'colors'=>ColorResource::collection($colors)->additional(['product_id' => $product_id]),
        ];
    }
}
