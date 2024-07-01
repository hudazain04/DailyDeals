<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification_Code extends Model
{
    use HasFactory;
    protected $fillable=['code','type','user_id','used'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
