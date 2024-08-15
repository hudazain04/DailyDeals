<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Merchant;
use App\Models\Store;

class Verification extends Model
{
    use HasFactory;
    protected $table ="verifications";
    protected $primaryKey="id";
    protected $fillable = ['commercial_record', 'merchant_id', 'store_id','status'];

    public function merchant()
    {
        return $this->belongsTo(User::class,'merchant_id');
    }


    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function setImageAttribute($image)
    {
        if ($image && $image->isValid()) {
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('CommercialImage'),$filename);
            $this->attributes['image'] = '/CommercialImage/'.$filename;
        }
    }

}
