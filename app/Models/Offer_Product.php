<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer_Product extends Model
{
    use HasFactory;
    protected $table ="offer_products";
    protected $primaryKey="id";
    protected $fillable = ['price_after','product_id','offer_id'];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
