<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Models\User;

class Category_Request extends Model
{
    use HasFactory;
    protected $table ="category_requests";
    protected $primaryKey="id";
    protected $fillable = ['category','admin_name','status','user_id','parent_category'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
