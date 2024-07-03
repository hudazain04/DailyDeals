<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Employee;
use App\Models\Store;
use App\Models\Verification;

class Merchant extends Model
{
    use HasFactory;
    protected $table ="merchants";
    protected $primaryKey="id";
    protected $fillable = ['verified','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function stores()
    {
        return $this->hasMany(Store::class);
    }
    public function verifications()
    {
        return $this->hasMany(Verification::class);
    }
}
