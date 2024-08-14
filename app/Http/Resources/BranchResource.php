<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
            'location' => $this->location,
            'google_maps' => $this->google_maps,
            'visible' => $this->visible,
            'store' => $this->store ? $this->store->name : null,
            'category' => $this->category ? $this->category->category : null,
            'image' => $this->image ? asset($this->image) : null,
            'rate'=> $this->rate,
            'numbers' => NumberResource::collection($this->numbers),
            'qr' => QrResource::collection($this->qrs)
        ];
    }
}
