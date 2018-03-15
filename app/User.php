<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'username', 
        'name', 
        'password', 
        'user_type', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'ip',
        'token',
        'expiration_date',
    ];

    public function dealer()
    {
        return $this->hasOne('App\Dealer', 'user_id', 'id');
    }
}
