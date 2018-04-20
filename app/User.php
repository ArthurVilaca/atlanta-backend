<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 
        'name', 
        'password', 
        'user_type', 
        'phone',
        'email',
        'adress',
        'adress_number',
        'adress_complement',
        'adress_district',
        'zip_code',
        'city',
        'state',
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

    public function findUserByEmail($email) {
        $user = DB::table('users')
            ->where('email', $email)
            ->first();
        return $user;
    }

    public function findUserByToken($token) {
        $user = DB::table('users')
            ->where('token', $token)
            ->first();
        return $user;
    }
}
