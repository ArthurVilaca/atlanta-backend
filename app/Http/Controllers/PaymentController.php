<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Response\Response;
use App\Client;
use App\Midia;
use \App\Service\UserService;

use Cielo\API30\Merchant;
use Cielo\API30\Ecommerce\Environment;
use Cielo\API30\Ecommerce\Sale;
use Cielo\API30\Ecommerce\CieloEcommerce;
use Cielo\API30\Ecommerce\Payment;
use Cielo\API30\Ecommerce\CreditCard;
use Cielo\API30\Ecommerce\Request\CieloRequestException;

class PaymentController extends Controller
{
    private $response;
    private $client;
    private $userService;

    public function __construct()
    {
        $this->response = new Response();
        $this->client = new Client();
        $this->userService = new UserService();
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
            $userLogged = $this->pageService->getAuthUser($request);

            // Configure o ambiente
            $environment = $environment = Environment::sandbox();

            // Configure seu merchant
            $merchant = new Merchant('MID', 'MKEY');

            // Crie uma instância de Sale informando o ID do pedido na loja
            $sale = new Sale('123');

            // Crie uma instância de Customer informando o nome do cliente
            $customer = $sale->customer('Fulano de Tal');

            // Crie uma instância de Payment informando o valor do pagamento
            $payment = $sale->payment(15700);

            // Crie uma instância de Credit Card utilizando os dados de teste
            // esses dados estão disponíveis no manual de integração
            $payment->setType(Payment::PAYMENTTYPE_CREDITCARD)
                    ->creditCard("123", CreditCard::VISA)
                    ->setExpirationDate("12/2018")
                    ->setCardNumber("0000000000000001")
                    ->setHolder("Fulano de Tal");

            // Configure o pagamento recorrente
            $payment->recurrentPayment(true)->setInterval(RecurrentPayment::INTERVAL_MONTHLY);

            // Crie o pagamento na Cielo
            try {
                // Configure o SDK com seu merchant e o ambiente apropriado para criar a venda
                $sale = (new CieloEcommerce($merchant, $environment))->createSale($sale);

                $recurrentPaymentId = $sale->getPayment()->getRecurrentPayment()->getRecurrentPaymentId();
            } catch (CieloRequestException $e) {
                // Em caso de erros de integração, podemos tratar o erro aqui.
                // os códigos de erro estão todos disponíveis no manual de integração.
                $error = $e->getCieloError();
            }

    
            $this->response->setType("S");
            // $this->response->setDataSet("midia", $returnMidia);
            $this->response->setMessages("Pagamento realizado com sucesso!");

        } catch (S3Exception $e) {
            $this->response->setType("N");
            $this->response->setMessages("Failed to create a midia!");
            return response()->json($this->response->toString(), 200);
        }

        return response()->json($this->response->toString(), 200);
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
