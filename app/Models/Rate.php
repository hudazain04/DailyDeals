<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Branch;
use App\Models\Customer;

class Rate extends Model
{
    use HasFactory;
    protected $table ="rates";
    protected $primaryKey="id";
    protected $fillable = ['rate', 'customer_id', 'branch_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
