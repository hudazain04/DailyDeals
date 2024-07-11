<?php

namespace App\Http\Resources;

use App\Types\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryRequestResource extends JsonResource
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
            'category'=>$this->category,
            'status'=>$this->status,
        ];
        if ($request->user()->role == UserType::Admin)
        {
            $baseData=array_merge($baseData,['admin_name'=>$this->admin_name,'user_id'=>$this-user_id]);
        }
        return $baseData;
    }
}
