<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Product_Info;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $sizes=$this->product_info()->size();
        $baseData=[
            'id'=>$this->id,
            'name'=>$this->name,
            'category'=>$this->category_id ? Category::find($this->category_id)->category : null,
            'sizes'=>SizeResource::collection($sizes),
        ];

        return $baseData;
    }
}
