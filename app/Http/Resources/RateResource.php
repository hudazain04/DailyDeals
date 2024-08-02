<?php

namespace App\Http\Resources;

use App\Types\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $baseData=[
            'rate'=>$this->rate,
//            'branch_id'=>$this->branch_id,

        ];
        if ($request->user()->role != UserType::Customer)
        {
            $baseData=array_merge($baseData,['customer_id'=>$this->customer_id]);

        }
        return $baseData;
    }
}
