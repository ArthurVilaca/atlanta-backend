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

    public function getByClientId($clientId)
    {
        $midias = DB::table('midias')->where('client_id', $clientId)->get();
        return $midias;
    }
}
