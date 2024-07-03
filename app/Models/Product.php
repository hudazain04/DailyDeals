<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Gift_Offer;
use App\Models\Extra_Offer;
use App\Models\Image;
use App\Models\Store;
use App\Models\Category;
use App\Models\Product_Info;

class Product extends Model
{
    use HasFactory;
    protected $table ="products";
    protected $primaryKey="id";
    protected $fillable = ['visible','name','category_id','store_id'];

    public function gift_offers()
    {
        return $this->hasMany(Gift_Offer::class);
    }
    public function extra_offers()
    {
        return $this->hasMany(Extra_Offer::class);
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function product_info()
    {
        return $this->hasMany(Product_Info::class);
    }
    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch__products');
    }
    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer__products');
    }
}
