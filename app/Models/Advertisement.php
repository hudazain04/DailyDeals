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
    protected $fillable = ['description','image','user_id','status','period',
    'price','shown','phone_number'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setImageAttribute($image)
    {
        if ($image && $image->isValid()) {
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('AdvertisementImage'), $filename);
            $this->attributes['image'] = $filename; 
        }
    }

    public function getImageUrlAttribute()
    {
        return url('/AdvertisementImage' . '/' . $this->attributes['image']);
    }
}
