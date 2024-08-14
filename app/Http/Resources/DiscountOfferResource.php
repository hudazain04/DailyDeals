<?php

namespace App\Http\Resources;

use App\Types\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountOfferResource extends JsonResource
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
           'discount'=>$this->discount_offer()->discount,
       ];
        if ($request->user()->role ===UserType::Merchant || $request->user()->role ===UserType::Employee )
        {
            array_merge($baseData,['active'=>$this->active]);
        }
       return $baseData;
    }
}
