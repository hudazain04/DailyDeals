<?php

namespace App\Http\Resources;

use App\Types\OfferType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SizePriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function __construct($resource, $productId = null,$offer)
    {
        parent::__construct($resource);
        $this->productId = $productId;
        $this->offer=$offer;
    }


    public function toArray(Request $request): array
    {

        $priceBefore = $this->price;
        $priceAfter = $priceBefore;

        $offer = $this->offer;

        if ($offer) {
            if ($offer->type == OfferType::Percentage) {
                $percentage=$offer->percentage_offer->percentage;
                $priceAfter = $priceBefore - ($priceBefore * ($percentage / 100));
            } elseif ($offer->type == OfferType::Discount) {
                $discount=$offer->discount_offer->discount;
                $priceAfter = $priceBefore - $discount;
            }
        }

        $colors = $this->resource->product_infos()
            ->where(['product_id' => $this->productId, 'size_id' => $this->id])
            ->with('color')
            ->get()
            ->pluck('color');

        $colorResources = collect($colors)->map(function ($color) use ($request) {
            return new ColorResource($color, $this->productId);
        });
        return [
            'id'=>$this->id,
            'size'=>$this->size,
            'unit'=>$this->unit,
            'price_before'=>$priceBefore,
            'price_after'=>$priceAfter,
            'colors'=>$colorResources,
        ];
    }
}
