<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



use App\Models\User;

class Advertisement extends Model
{
    use HasFactory;
    protected $table ="advertisements";
    protected $primaryKey="id";
    protected $fillable = ['description','image','user_id','approved','period',
    'price','shown','invoice','phone_number'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
