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
    protected $fillable = ['commercial_record', 'merchant_id', 'store_id'];

    public function merchant()
    {
        return $this->belongsTo(User::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
