<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    protected $fillable = [
        "registration_code", 'user_id'
    ];
}
