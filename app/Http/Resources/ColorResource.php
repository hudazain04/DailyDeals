<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use function Symfony\Component\VarExporter\Internal\f;

class ColorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    protected $productId;

    public function __construct($resource, $productId = null)
    {
        parent::__construct($resource);
        $this->productId = $productId;
    }

    public function toArray(Request $request): array
    {


            $images = $this->resource->images()
                ->where(['product_id'=> $this->productId,'color_id'=>$this->id])
                ->get();

            $firstImage = $images->first();


        return [
            'id'=>$this->id,
          'color'=>$this->resource->color,
            'hex_code' => $this->hex_code,
          'image'=> $firstImage ? asset($firstImage->image) : null,
        ];
    }
}
