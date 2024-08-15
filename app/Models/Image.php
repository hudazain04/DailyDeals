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
    public function setImageAttribute($image)
    {
        if ($image && $image->isValid()) {
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('ProductImage'),$filename);
            $this->attributes['image'] = '/ProductImage/'.$filename;
        }
    }





}
