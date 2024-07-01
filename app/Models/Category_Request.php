<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Models\User;

class Category_Request extends Model
{
    use HasFactory;
    protected $fillable = ['category', 'admin_name', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
