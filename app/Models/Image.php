<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Product;
use App\Models\Color;

class Image extends Model
{
    use HasFactory;
    protected $table ="images";
    protected $primaryKey="id";
    protected $fillable = ['image','product_id','color_id'];
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
