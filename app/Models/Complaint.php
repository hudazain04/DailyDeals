<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Branch;
use App\Models\Customer;

class Complaint extends Model
{
    use HasFactory;
    protected $table ="complaints";
    protected $primaryKey="id";
    protected $fillable = ['branch_id', 'customer_id', 'complaint'];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
