<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Image;

class Color extends Model
{    
    use HasFactory;
    protected $table ="colors";
    protected $primaryKey="id";
    protected $fillable = ['color'];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function product_infos()
    {
        return $this->hasMany(Product_Info::class);
    }
}
