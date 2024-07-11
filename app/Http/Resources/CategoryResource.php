<?php

namespace App\Http\Resources;

use App\Types\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $baseData=[
            'category'=>$this->category,
            'parent_category'=>$this->parent_category,
            'priority'=>$this->priority,
        ];
        if ($request->user()->role == UserType::Admin)
        {
            $baseData=array_merge($baseData,['visible'=>$this->visible]);
        }
        return $baseData;
    }
}
