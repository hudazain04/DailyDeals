<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QR extends Model
{
    use HasFactory;
    protected $table ="q_r_s";
    protected $primaryKey="id";
    protected $fillable = ['branch_id', 'image','rate'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

//    store path in controller
//    public function setImageAttribute($image)
//    {
//        if ($image && $image->isValid()) {
//            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
//            $image->move(public_path('QrImage'),$filename);
//            $this->attributes['image'] = public_path('QrImage').$filename;
//        }
//    }



//    public function getImageUrlAttribute()
//    {
//        return $this->attributes['image'];
//    }
}
