<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Offer;
use App\Models\Product;

class Extra_Offer extends Model
{
    use HasFactory;
    protected $table ="extra_offers";
    protected $primaryKey="id";
    protected $fillable = ['product_count','extra_count','product_id','offer_id'];
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
