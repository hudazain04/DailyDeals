<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $table ="devices";
    protected $primaryKey="id";
    protected $fillable=['user_id','device_name','token','device_id','notification_token'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
