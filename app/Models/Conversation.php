<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Message;

class Conversation extends Model
{
    use HasFactory;
    protected $table ="conversations";
    protected $primaryKey="id";
    protected $fillable = ['customer_id','employee_id'];

    public function customer()
    {
        return $this->belongsTo(User::class,'customer_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
