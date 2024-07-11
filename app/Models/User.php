<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    /*protected $fillable = [
        'name',
        'email',
        'password',
    ];*/

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'image',
        'role',
        'blocked',
        'verified',
    ];
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }

    public function category_requests()
    {
        return $this->hasMany(Category_Request::class);
    }
  /*  public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }*/
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function type_of_offer_requests()
    {
        return $this->hasMany(Type_Of_Offer_Request::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification__users');
    }
    public function verificaion_codes()
    {
        return $this->hasMany(Verification_Code::class);
    }
    public function devices()
    {
        return $this->hasMany(Device::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    public function notifieds()
    {
        return $this->hasMany(Notified::class);
    }
    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function stores()
    {
        return $this->hasMany(Store::class);
    }
    public function verifications()
    {
        return $this->hasMany(Verification::class);
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
