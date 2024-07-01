<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Branch;

class Number extends Model
{
    use HasFactory;
    protected $fillable = ['phone_number', 'branch_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
