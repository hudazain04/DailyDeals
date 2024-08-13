<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ColorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product_id = $this->additional['product_id'];

        if (! $this->image)
        {
            $image= $this->image()->where(['product_id'=>$product_id]);
        }
        else
        {
            $image=$this->image;
        }
        return [
          'color'=>$this->color,
          'image'=>$image,
        ];
    }
}
