<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Comment;
use App\Models\Discount_Offer;
use App\Models\Extra_Offer;
use App\Models\Gift_Offer;
use App\Models\Percentage_Offer;

class Offer extends Model
{
    use HasFactory;
    protected $table ="offers";
    protected $primaryKey="id";
    protected $fillable = ['type','name','image','active','period'];

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'offer_branches');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_products');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function discount_offer()
    {
        return $this->hasOne(Discount_Offer::class);
    }
    public function extra_offer()
    {
        return $this->hasOne(Extra_Offer::class);
    }
    public function gift_offer()
    {
        return $this->hasOne(Gift_Offer::class);
    }
    public function percentage_offer()
    {
        return $this->hasOne(Percentage_Offer::class);
    }

    public function setImageAttribute($image)
    {
        if ($image && $image->isValid()) {
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('OfferImage'),$filename);
            $this->attributes['image'] = '/OfferImage/'.$filename;
        }
    }

}

