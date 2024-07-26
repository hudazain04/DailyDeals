<?php

namespace App\Http\Resources;

use App\Types\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;


class FavoriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
    return [
    'branch' => $this->branch ? $this->branch->name : null,
    'customer' => $this->customer ? $this->customer->first_name . " " . $this->customer->last_name : null,
        ];
    }
}
