<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListAdvertisementResource extends JsonResource
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
            'user' => $this->user ? $this->user->first_name . " " . $this->user->last_name : null,
            'image' => $this->image,
        ];
    }
}
