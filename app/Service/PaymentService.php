<?php 
namespace App\Service;

use Illuminate\Http\Request;
use App\Payment;

class PaymentService extends Service
{
    private $payment;

    public function __construct()
    {
        $this->payment = new Payment();
    }

    public function create($amount, $month_reference, $paymentId, $status, $returnMessage, $card_number, $issue_date, $users_id)
    {
        $payment = $this->payment->create([
            'amount' => $amount,
            'month_reference' => $month_reference,
            'paymentId' => $paymentId,
            'status' => $status,
            'returnMessage' => $returnMessage,
            'card_number' => $card_number,
            'issue_date' => $issue_date,
            'users_id' => $users_id
        ]);
        return $payment;
    }
}