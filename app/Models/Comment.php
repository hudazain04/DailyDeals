<?php

namespace App\Models;

use Cassandra\Custom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Models\Offer;
use App\Models\Customer;

class Comment extends Model
{
    use HasFactory;
    protected $table ="comments";
    protected $primaryKey="id";
    protected $fillable = ['comment', 'customer_id','offer_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
