<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Models\Store;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\Employee;
use App\Models\Favorite;
use App\Models\Notified;
use App\Models\Number;
use App\Models\QR;
use App\Models\Rate;

class Branch extends Model
{
    use HasFactory;
    protected $table ="branches";
    protected $primaryKey="id";
    protected $fillable = ['name','location','google_maps','store_id','category_id','visible','image'];


    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    public function notifieds()
    {
        return $this->hasMany(Notified::class);
    }
    public function numbers()
    {
        return $this->hasMany(Number::class);
    }
    public function qrs()
    {
        return $this->hasMany(QR::class);
    }
    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer__branches');
    }
}
