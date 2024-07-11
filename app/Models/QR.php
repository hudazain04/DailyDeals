<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Branch;

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
}
