<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'phone_number' => $this->phone_number,
            'period' => $this->period,
            'user' => $this->user ? $this->user->first_name . " " . $this->user->last_name : null,
            'price' => $this->price,
            'shown' => $this->shown,
            'status' => $this->status,
            'image' => url('/AdvertisementImage'.'/' . $this->image),
            'accepted_at' => $this->accepted_at,
        ];
    }
}
