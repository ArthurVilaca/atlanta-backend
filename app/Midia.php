<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Midia extends Model
{
    protected $fillable = [
        'client_id',
        'url',
        'keyname'
    ];
}
