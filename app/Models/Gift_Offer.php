<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Offer;
use App\Models\Product;

class Gift_Offer extends Model
{
    use HasFactory;
    protected $table ="gift_offers";
    protected $primaryKey="id";
    protected $fillable = ['product_id','offer_id'];

      public function offer()
      {
          return $this->belongsTo(Offer::class);
      }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
