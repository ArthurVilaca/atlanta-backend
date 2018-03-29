<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Billspay extends Model
{
    protected $fillable = [];

    /**
     * Clientes com atraso
     */
    public function latePaymentsClients()
    {
        $clients = DB::table('billsreceives')
            ->select('clients.id', 'clients.name')
            ->join('clients', 'clients.id', '=', 'billsreceives.client_id')
            ->whereNull('billsreceives.payment_date')
            ->get();

        return $clients;
    }

    /**
     * 
     */
    public function paymentsLateDealer()
    {
        $paymentLate = DB::table('billsreceives');

        return $paymentLate;
    }

    /**
     * Debitos aberto pelo cielnte
     */
    public function openDebitsClient($clientID)
    {
        $client = DB::table('billsreceives')
            ->join('clients', 'clients.id', '=', 'billsreceives.client_id')
            ->whereNull('billsreceives.payment_date')
            ->where('billsreceives.client_id', $clientID)
            ->get();
        
        return $client;
    }

    /**
     * Debitos já pagos pelo cliente
     */
    public function payDebitsClient($clientID)
    {
        $client = DB::table('billsreceives')
            ->join('clients', 'clients.id', '=', 'billsreceives.client_id')
            ->whereNotNull('billsreceives.payment_date')
            ->where('billsreceives.client_id', $clientID)
            ->get();
        
        return $client;
    }
}
