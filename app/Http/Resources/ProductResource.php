<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product_Info;
use App\Models\Size;
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



        $hasSize = array_key_exists('size', $this->additional);
        if ($hasSize == false)
        {
            $sizes = $this->product_info()
                ->where(['product_id' => $this->id])
                ->with('size')
                ->get()
                ->pluck('size')
                ->unique('id');
//
//            $sizes=$this->resources
//           return [$sizes];

            $sizeResources = collect($sizes)->map(function ($size) use ($request) {
                return new SizeResource($size, $this->id);
            });
            $baseData=[
                'id'=>$this->id,
                'name'=>$this->name,
                'category'=>$this->category_id ? Category::find($this->category_id)->category : null,
                'sizes'=>$sizeResources
//            'sizes' => SizeResource::collection(Size::whereIn('id' , $this->product_info->pluck('size_id'))->get())
//        'sizes' => $sizes
            ];
//            $baseData['sizes'] = $sizeResources;

        }
        $baseData=[
            'id'=>$this->id,
            'name'=>$this->name,
            'category'=>$this->category_id ? Category::find($this->category_id)->category : null,
        ];
        return $baseData;
    }
}
