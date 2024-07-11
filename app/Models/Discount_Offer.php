<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Offer;

class Discount_Offer extends Model
{
    use HasFactory;
    protected $table ="discount_offers";
    protected $primaryKey="id";
    protected $fillable=['discount','offer_id'];
    public function offer()
     {
     return $this->belongsTo(Offer::class);
     }
}

