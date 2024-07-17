<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Merchant;
use App\Models\Branch;

class Employee extends Model
{
    use HasFactory;
    protected $table ="employees";
    protected $primaryKey="id";
    protected $fillable = ['code','branch_id','user_id','merchant_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function merchant()
    {
        return $this->belongsTo(User::class,'merchant_id');
    }
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    protected function casts(): array
    {
        return [
            'code' => 'hashed',
        ];
    }
}
