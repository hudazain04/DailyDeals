<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ListCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'category'=>$this->category,
            'parent_category_id'=> $this->parent_category,
            'parent_category_name'=> $this->parent_category != null ? $this->parent->category : null ,
            'priority'=>$this->priority,
            'visible' => $this->when(auth()->user()->role == 'Admin', $this->visible),
        ];
    }
}
