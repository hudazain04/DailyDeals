<?php

namespace App\Http\Resources;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SizeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function __construct($resource, $productId = null)
    {
        parent::__construct($resource);
        $this->productId = $productId;
    }


    public function toArray(Request $request): array
    {
//        dd($this->resource);
//        if(! $this->resource->colors)
//        {
//            $infos=$this->resource->product_infos()
//                ->where(['product_id'=> $this->productId])
//                ->get();
//        }
//        else
//        {
//            $colors=$this->resource->colors;
//        }

        $colors = $this->resource->product_infos()
            ->where(['product_id' => $this->productId, 'size_id' => $this->id])
            ->with('color')
            ->get()
            ->pluck('color');

        $colorResources = collect($colors)->map(function ($color) use ($request) {
//            $colorModel = Color::find($color['color']);
            return new ColorResource($color, $this->productId);
        });
//        dd($colorResources);
        return [
          'size'=>$this->size,
          'unit'=>$this->unit,
          'price'=>$this->price,
          'colors'=>$colorResources,
        ];
    }
}
