<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = [];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
