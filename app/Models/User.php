<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'department_id',
        'manager_id',
        'address_id',
        'user_type_id',
        'first_name',
        'last_name',
        'email',
        'rank',
        'date_of_birth',
        'phone_nb',
        'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'id');
    }

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class, 'driver_id', 'id');
    }
    public function employees()
    {
        return $this->hasMany(User::class, 'manager_id', 'id');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'address_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
