<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    protected $fillable = [
    'name',
    'email',
    'password',
    'phone', 
    'role',
    'city',
    'blood_group',
    'is_verified',
    'status_availabality',
];
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

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








public function centre(){
    return $this->hasOne(Centre::class);
}
public function hopital(){
    return $this->hasOne(Hopital::class);
}
public function reports(){
        $this->hasMany(Report::class);
}
public function donations(){
        $this->hasMany(Donation::class);
}
}
