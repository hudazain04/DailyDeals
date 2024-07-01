<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Models\Branch;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['category', 'parent_category', 'visible', 'priority'];

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_category');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_category');
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
