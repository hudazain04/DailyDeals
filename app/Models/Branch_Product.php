<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch_Product extends Model
{
    use HasFactory;
    protected $table ="branch_products";
    protected $primaryKey="id";
    protected $fillable = ['visible','branch_id','product_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
