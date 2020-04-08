<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

use Hash;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'provider', 'provider_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime', 
    ];

    //Generate password for new user
    public static function generatePassword() 
    {
        // Generate random string and encrypt it. 
        return Hash::make(Str::random(35));
    }

    //Relationships
    public function company() 
    {
        return $this->belongsTo('\App\Company')
        ->withDefault([
            'slug' => 0
        ]);
    }

    public function station() 
    {
        return $this->belongsTo('\App\Station')
        ->withDefault([
            'slug' => 0
        ]);
    }

    public function employee() 
    {
        return $this->belongsTo('\App\Employee');
    }
}
