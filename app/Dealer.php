<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Dealer extends Model
{
    protected $fillable = [
        "registration_code", 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function getDealerByUserId($userID)
    {
        $user = DB::table('dealers')->where('user_id', $userID)->first();

        return $user;
    }
}
