<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'phone_number',
        'email',
        'name',
        'first_name',
        'last_name',
        'profile_img_url',
        'role',
        'gender',
        'birth_date',
        'major_id',
        'country_id',
        'province_id',
        'city_id',
        'progress',
        'xp',
        'heart',
        'heart_refilled_at',
        'gems',
        'daily_goal',
        'current_streak',
        'longest_streak',
        'streak_freezes_available',
        'last_active_at',
        'otp_code',
        'otp_code_expiration',
        'refresh_token',
        'last_logout',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
        'otp_code_expiration',
        'refresh_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'progress' => 'array',
        'birth_date' => 'date',
        'heart_refilled_at' => 'datetime',
        'last_active_at' => 'datetime',
        'otp_code_expiration' => 'datetime',
        'last_logout' => 'datetime',
    ];

    public function major()
    {
        return $this->belongsTo(Major::class)->withDefault();
    }

    public function country()
    {
        return $this->belongsTo(Country::class)->withDefault();
    }

    public function province()
    {
        return $this->belongsTo(Province::class)->withDefault();
    }

    public function city()
    {
        return $this->belongsTo(City::class)->withDefault();
    }

    public function xpTransactions()
    {
        return $this->hasMany(XpTransaction::class);
    }

    public function quests()
    {
        return $this->hasMany(UserQuest::class);
    }

    public function achievements()
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'id' => $this->id,
            'phone_number' => $this->phone_number,
            'role' => $this->role,
            'last_logout' => $this->last_logout ? $this->last_logout->timestamp : 0,
        ];
    }
}
