<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;
    protected $table ="sizes";
    protected $primaryKey="id";
    protected $fillable = ['size', 'unit','price'];

    public function product_infos()
    {
        return $this->hasMany(Product_Info::class);
    }

}
