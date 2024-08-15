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

        $baseData=[
            'id'=>$this->id,
            'name'=>$this->name,
            'category'=>$this->category_id ? Category::find($this->category_id)->category : null,

        ];

        $hasSize = array_key_exists('size', $this->additional);
        if ($hasSize == false)
        {
            $sizes = $this->resource->product_info()
                ->where(['product_id' => $this->id])
                ->with('size')
                ->get()
                ->pluck('size');

            $sizeResources = collect($sizes)->map(function ($size) use ($request) {
                return new SizeResource($size, $this->id);
            });
            $baseData['sizes'] = $sizeResources;
        }

        return $baseData;
    }
}
