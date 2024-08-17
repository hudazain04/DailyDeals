<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Types\OfferType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $sizes = $this->product_info()
            ->where(['product_id' => $this->id])
            ->with('size')
            ->get()
            ->pluck('size')
            ->unique('id')
            ->values();
        $sizeResources = collect($sizes)->map(function ($size) use ($request) {
            return new SizePriceResource($size,$this->id ,$this->offer);});

        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'category'=>$this->category_id ? Category::find($this->category_id)->category : null,
            'sizes'=>$sizeResources,

        ];
    }
}
