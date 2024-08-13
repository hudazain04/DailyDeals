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
            case OfferType::Percentage :
                array_merge($baseData,['percentage'=>$this->percentage_offer()->percentage,]);
                break;
            case OfferType::Discount :
                array_merge($baseData,['discount'=>$this->discount_offer()->discount,]);
                break;
            case OfferType::Extra :
                array_merge($baseData,['product_count'=>$this->extra_offer()->product_count,
                    'extra_count'=>$this->extra_offer()->extra_count,]);


        }
        if ($request->user()->role ===UserType::Merchant || $request->user()->role ===UserType::Employee )
        {
            array_merge($baseData,['active'=>$this->active]);
        }
        return $baseData;    }
}
