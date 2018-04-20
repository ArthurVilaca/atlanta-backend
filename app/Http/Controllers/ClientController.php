<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuthException;
use JWTAuth;
use App\Client;
use App\User;
use \App\Response\Response;
use \App\Service\UserService;
use \App\Service\ClientService;

use App\Http\Controllers\EmailsController;

class ClientController extends Controller
{
    private $client;
    private $response;
    private $userService;
    private $clientService;
    private $emailsController;

    public function __construct(EmailsController $emailsController)
    {
        $this->client = new Client();
        $this->user = new User();
        $this->response = new Response();
        $this->userService = new UserService();
        $this->clientService = new ClientService();
        $this->emailsController = $emailsController;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $this->userService->getAuthUser($request);

        if($user->user_type == "U")
        {
            $client = $this->client->get();
        }
        else if($user->user_type == "D")
        {
            $dealer = $this->client->getDealerId($user->id);
            if($dealer)
            {
                $client = $this->client->getClientByDealer($dealer->id);
                foreach ($client as $key => $value) {
                    $value->user = $this->user->find($value->user_id);
                }
            }
        }
        
        $user->client = $client;

        $this->response->setType("S");
        $this->response->setDataSet("User", $user);
        $this->response->setMessages("Dealers search successfully!");
        
        return response()->json($this->response->toString(), 200);
    }

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
        $userLogged = $this->userService->getAuthUser($request);
        $userType = $userLogged->user_type;
        
        if ($userType == "D")
        {
            $dealer = $this->client->getDealerId($userLogged->id);
            if($dealer)
            {
                $returnUser = $this->userService->create($request);
        
                $returnClient = $this->clientService->create($request, $returnUser->id, $dealer->id);
                $this->emailsController->send('Bem Vindo ao Httplay', $request->get('email'), '[HTTPLAY] - Confirmação de cadastro');

                $this->response->setType("S");
                $this->response->setDataSet("user", $returnUser);
                $this->response->setDataSet("client", $returnClient);
                $this->response->setMessages("Created user successfully!");
    
                return response()->json($this->response->toString(), 200);
            }
            else 
            {
                $this->response->setType("N");
                $this->response->setMessages("Error.");
            }
        }
        else 
        {
            $this->response->setType("N");
            $this->response->setMessages("You don't have permission to create a client.");
            
            return response()->json($this->response->toString(), 200);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = $this->client->find($id);
        $user = $this->user->find($client->user_id);

        if(!$user) 
        {
            $this->response->settype("N");
            $this->response->setMessages("Record not find!");

            return response()->json($this->response->toString(), 404);
        }

        $this->response->setType("S");
        $this->response->setDataSet("user", $user);
        $this->response->setDataSet("client", $client);
        $this->response->setMessages("Sucess, client updated!");

        return response()->json($this->response->toString(), 200);
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
        $client = $this->client->find($id);
        $user = $this->user->find($client->user_id);

        if(!$user) 
        {
            $this->response->settype("N");
            $this->response->setMessages("Record not find!");

            return response()->json($this->response->toString(), 404);
        }

        $user_data = $request->all();
        
        $client->fill($user_data);
        $client->save();
        
        $user_data['id'] = $user_data['user_id'];
        $user->fill($user_data);
        $user->save();

        $this->response->setType("S");
        $this->response->setDataSet("user", $user);
        $this->response->setDataSet("client", $client);
        $this->response->setMessages("Sucess, client updated!");

        return response()->json($this->response->toString(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = $this->client->find($id);
        $user = $this->user->find($client->user_id);

        if(!$user) 
        {
            $this->response->settype("N");
            $this->response->setMessages("Record not find!");

            return response()->json($this->response->toString(), 404);
        }

        $client->delete();
        $user->delete();
    }
}
