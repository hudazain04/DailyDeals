<?php

namespace App\Http\Resources;

use App\Types\OfferType;
use App\Types\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'image'=>$this->image ? asset($this->image) : null,
            'type'=>$this->type,
        ];
        switch ($this->type)
        {
            case OfferType::Percentage:
                $baseData = array_merge($baseData, ['percentage' => $this->percentage_offer ? $this->percentage_offer->percentage : null]);
                break;
            case OfferType::Discount:
                $baseData = array_merge($baseData, ['discount' => $this->discount_offer ? $this->discount_offer->discount : null]);
                break;
            case OfferType::Extra:
                $baseData = array_merge($baseData, [
                    'product_count' => $this->extra_offer ? $this->extra_offer->product_count : null,
                    'extra_count' => $this->extra_offer ? $this->extra_offer->extra_count : null,
                ]);
                break;
        }

        if ($request->user()->role ===UserType::Merchant || $request->user()->role ===UserType::Employee )
        {
            array_merge($baseData,['active'=>$this->active]);
        }
        return $baseData;    }
}
