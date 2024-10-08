<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'visible' => $this->visible,
            'verified' => $this->verified,
            'merchant_id' => $this->merchant->id,
            'merchant' => $this->merchant->first_name ." ".$this->merchant->last_name,
        ];
    }
}
