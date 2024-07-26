<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ComplaintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'complaint' => $this->complaint,
            'branch' => $this->branch ? $this->branch->name : null,
            'customer' => $this->customer ? $this->customer->first_name . " " . $this->customer->last_name : null,
        ];
    }
}
