<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuthException;
use JWTAuth;
use \App\Response\Response;

use App\Client;
use App\Payments;
use \App\Service\UserService;
use \App\Service\PaymentService;

use Cielo\API30\Merchant;
use Cielo\API30\Ecommerce\Environment;
use Cielo\API30\Ecommerce\Sale;
use Cielo\API30\Ecommerce\CieloEcommerce;
use Cielo\API30\Ecommerce\Payment;
use Cielo\API30\Ecommerce\CreditCard;
use Cielo\API30\Ecommerce\Request\CieloRequestException;
use Cielo\API30\Ecommerce\RecurrentPayment;

use App\Http\Controllers\EmailsController;

class PaymentController extends Controller
{
    private $response;
    private $client;
    private $userService;
    private $paymentService;
    private $emailsController;

    public function __construct(EmailsController $emailsController)
    {
        $this->response = new Response();
        $this->client = new Client();
        $this->userService = new UserService();
        $this->paymentService = new PaymentService();
        $this->emailsController = $emailsController;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $userLogged = $this->userService->getAuthUser($request);

            // Configure o ambiente
            $environment = $environment = Environment::sandbox();

            // Configure seu merchant
            $merchant = new Merchant(env('CIELO_MERCHANT_ID'), env('CIELO_MERCHANT_KEY'));

            // Crie uma instância de Sale informando o ID do pedido na loja
            $id = date('YmdHis');
            $sale = new Sale($id);

            // Crie uma instância de Customer informando o nome do cliente
            $customer = $sale->customer($userLogged->name);

            $amount = 68.9;
            // Crie uma instância de Payment informando o valor do pagamento
            $payment = $sale->payment($amount);

            // Crie uma instância de Credit Card utilizando os dados de teste
            // esses dados estão disponíveis no manual de integração
            $payment->setType(Payment::PAYMENTTYPE_CREDITCARD)
                    ->creditCard($request->get('cvv'), CreditCard::VISA)
                    ->setExpirationDate($request->get('due_date'))
                    ->setCardNumber(str_replace(" ", "", $request->get('card_number')))
                    ->setHolder($request->get('name'));

            // Configure o pagamento recorrente
            $payment->recurrentPayment(true)->setInterval(RecurrentPayment::INTERVAL_MONTHLY);

            // Crie o pagamento na Cielo
            try {
                // Configure o SDK com seu merchant e o ambiente apropriado para criar a venda
                $sale = (new CieloEcommerce($merchant, $environment))->createSale($sale);

                $paymentId = $sale->getPayment()->getPaymentId();
                $status = $sale->getPayment()->getStatus();
                $returnMessage = $sale->getPayment()->getReturnMessage();
                $recurrentPaymentId = $sale->getPayment()->getRecurrentPayment()->getRecurrentPaymentId();
                $nextRecurrency = $sale->getPayment()->getRecurrentPayment()->getNextRecurrency();
                $cardNumber = $sale->getPayment()->getCreditCard()->getCardNumber();

                $payment = $this->paymentService->create($amount, date('Y-m'), $paymentId, $status, $returnMessage, $cardNumber, new \DateTime(), $userLogged->id);

                if($status != 1) {
                    $this->response->setType("N");
                    $this->response->setMessages("Falha para processar o pagamento!");
                    return response()->json($this->response->toString(), 200);
                } else {
                    $this->emailsController->send(
                        '<br>Ola,<br>Pagamento recebido com sucesso no cartão: '.$cardNumber.'<br>Valor: R$ 68,90<br>Proxima cobrança: '.(new \DateTime($nextRecurrency))->format('d/m/Y').'<br>Att<br>Equipe Httplay',
                        $userLogged->email,
                        '[HTTPLAY] - Confirmação de pagamento'
                    );

                    $this->response->setType("S");
                    $this->response->setMessages("Pagamento processado com sucesso!");
                    return response()->json($this->response->toString(), 200);
                }

            } catch (CieloRequestException $e) {
                // Em caso de erros de integração, podemos tratar o erro aqui.
                // os códigos de erro estão todos disponíveis no manual de integração.
                $error = $e->getCieloError();

                $this->response->setType("N");
                $this->response->setMessages("Falha para processar o pagamento!");
                return response()->json($this->response->toString(), 200);
            }

        } catch (Exception $e) {
            $this->response->setType("N");
            $this->response->setMessages("Falha para processar o pagamento!");
            return response()->json($this->response->toString(), 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
