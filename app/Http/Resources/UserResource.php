<?php

namespace App\Http\Resources;

use App\Types\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $baseData = [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'image' => $this->image,

        ];
        if(Auth::user())
        {
            if (Auth::user()->role == UserType::Admin ){
                $baseData = array_merge($baseData , ['role' => $this->role]);
            }
        }


        return $baseData;
    }
}
