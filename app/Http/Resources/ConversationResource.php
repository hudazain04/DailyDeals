<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $currentUserId=$request->user()->id;
        $otherUserID = $this->user1_id == $currentUserId ? $this->user2_id : $this->user1_id;
        $otherUser=User::find($otherUserID);
        $lastMessage = $this->messages()->latest()->first();

        return [
          'id'=>$this->id,
          'last_message'=>$lastMessage->message,
            'read'=>$lastMessage->read,
            'time'=>$lastMessage->created_at->format('h:i A'),
            'other_user_name'=>$otherUser->full_name,
            'other_user_image'=>$otherUser->image ? asset($otherUser->image) : null,
        ];
    }
}
