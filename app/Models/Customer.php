<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Comment;
use App\Models\Complaint;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Notified;
use App\Models\Rate;

class Customer extends Model
{
    use HasFactory;
    protected $table ="customers";
    protected $primaryKey="id";
    protected $fillable = ['user_id'];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    public function notifieds()
    {
        return $this->hasMany(Notified::class);
    }
    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
}
