<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Branch;
use App\Models\Customer;

class Favorite extends Model
{
    use HasFactory;
    protected $table ="favorites";
    protected $primaryKey="id";
    protected $fillable = ['branch_id','customer_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function customer()
    {
        return $this->belongsTo(User::class);
    }
}
