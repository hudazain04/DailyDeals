<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
          'message'=>$this->message,
          'read'=>boolval($this->read),
          'time'=>$this->created_at->format('h:i A'),
          'mine'=>$this->sender_id === $request->user()->id,
        ];
    }
}
