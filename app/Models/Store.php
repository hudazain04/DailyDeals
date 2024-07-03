<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Branch;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\Verification;
class Store extends Model
{
    use HasFactory;
    protected $table ="stores";
    protected $primaryKey="id";
    protected $fillable = ['name','description','type','visible','merchant_id'];

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
    public function verifications()
    {
        return $this->hasMany(Verification::class);
    }
}
