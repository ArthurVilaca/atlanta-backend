<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Client extends Model
{
    protected $fillable = [
        'registration_code',
        'company_branch',
        'sale_plan',
        'dealer_id',
        'user_id'
    ];

    public function getDealerId($userID)
    {
        $dealer = DB::table('dealers')->where('user_id', $userID)->first();
        
        return $dealer;
    }

    public function getClientByDealer($dealerID)
    {
        $client = DB::table('clients')->where('dealer_id', $dealerID)->get();

        return $client;
    }
    
    public function getClientByUser($userID)
    {
        $client = DB::table('clients')->where('user_id', $userID)->first();
        return $client;
    }
}
