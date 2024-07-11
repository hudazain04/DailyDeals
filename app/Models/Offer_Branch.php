<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer_Branch extends Model
{
    use HasFactory;
    protected $table ="offer_branches";
    protected $primaryKey="id";
    protected $fillable = ['active','branch_id','offer_id'];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
