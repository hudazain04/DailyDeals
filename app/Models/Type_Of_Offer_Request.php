<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Type_Of_Offer_Request extends Model
{
    use HasFactory;
    protected $table ="type_of_offer_requests";
    protected $primaryKey="id";
    protected $fillable = ['description','type','user_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
