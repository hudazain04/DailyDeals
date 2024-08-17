<?php

namespace App\Http\Resources;

use App\Models\Discount_Offer;
use App\Models\Extra_Offer;
use App\Models\Percentage_Offer;
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
//        dd($this->gift_offer());

        $baseData=[
            'id'=>$this->id,
//            'name'=>$this->name,
            'image'=>$this->image ? asset($this->image) : null,
            'type'=>$this->type,
        ];
        switch ($this->type)
        {
            case OfferType::Percentage:
//                $percentageOffer = $this->percentage_offer();
                $percentageOffer=Percentage_Offer::where('offer_id',$this->id)->first();
                $baseData = array_merge($baseData, ['percentage' => $percentageOffer ? $percentageOffer->percentage : null]);
                break;
            case OfferType::Discount:
//                $discountOffer = $this->discount_offer();
                $discountOffer=Discount_Offer::where('offer_id',$this->id)->first();
                $baseData = array_merge($baseData, ['discount' => $discountOffer ? $discountOffer->discount : null]);
                break;
            case OfferType::Extra:
//                $extraOffer = $this->extra_offer();
                $extraOffer=Extra_Offer::where('offer_id',$this->id)->first();
                $baseData = array_merge($baseData, [
                    'product_count' => $extraOffer ? $extraOffer->product_count : null,
                    'extra_count' => $extraOffer ? $extraOffer->extra_count : null,
                ]);
                break;
        }

        if ($request->user()->role ===UserType::Merchant || $request->user()->role ===UserType::Employee )
        {
            array_merge($baseData,['active'=>$this->active]);
        }
        return $baseData;    }
}
