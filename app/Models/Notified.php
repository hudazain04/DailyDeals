<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Customer;
use App\Models\Branch;

class Notified extends Model
{
    use HasFactory;
    protected $table ="notifieds";
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
