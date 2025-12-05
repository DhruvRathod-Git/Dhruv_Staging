<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Designation;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'number',
        'designation',
        'department',
        'location'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function designation(): HasOne
    {
        return $this->hasOne(Designation::class, 'id', 'designation');
    }

     public function department(): HasOne
    {
        return $this->hasOne(Department::class, 'id', 'department');
    }

     public function country(): HasOne
    {
        return $this->hasOne(Country::class, 'country');
    }

     public function state(): HasOne
    {
        return $this->hasOne(State::class,'country_id', 'country');
    }

     public function city(): HasOne
    {
        return $this->hasOne(City::class, 'state_id', 'country');
    }
    
    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leave(): HasMany
    {
        return $this->hasMany(Leave::class);
    }
    
}