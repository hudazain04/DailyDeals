<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Conversation;

class Message extends Model
{
    use HasFactory;
    protected $table ="messages";
    protected $primaryKey="id";
    protected $fillable = ['message', 'sender_id', 'receiver_id', 'conversation_id','time'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
