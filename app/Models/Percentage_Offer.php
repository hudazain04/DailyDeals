<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Percentage_Offer extends Model
{
    use HasFactory;
    protected $table = 'offers';
    protected $fillable=['percentage','offer_id'];

   /* public function offer()
    {
        return $this->belongsTo(Offer::class);
    }*/
}
