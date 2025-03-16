<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
        'phone'
    ];

    protected $with = ['transactions', 'targets', 'reports', 'categories', 'logs', 'recommendations', 'budgets'];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'password' => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
    public function targets(){
        return $this->hasMany(Target::class);
    }
    public function reports(){
        return $this->hasMany(Report::class);
    }
    public function categories(){
        return $this->hasMany(Category::class);
    }
    public function logs(){
        return $this->hasMany(Log::class);
    }
    public function recommendations(){
        return $this->hasMany(Recommendation::class);
    }
    public function budgets(){
        return $this->hasMany(Budget::class);
    }
}