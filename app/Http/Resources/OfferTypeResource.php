<?php

namespace App\Http\Resources;

use App\Types\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class OfferTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $baseData=[
          'type'=>$this->type,
          'description'=>$this->description,
        ];
        if (Auth::user()->role===UserType::Admin)
        {
            $baseData=array_merge($baseData,['user_id'=>$this->user_id]);
        }
        return $baseData;
    }
}
