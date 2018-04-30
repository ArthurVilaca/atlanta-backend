<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Payment extends Model
{
    protected $fillable = [
        'amount',
        'month_reference',
        'paymentId',
        'status',
        'returnMessage',
        'card_number',
        'issue_date',
        'users_id'
    ];

}