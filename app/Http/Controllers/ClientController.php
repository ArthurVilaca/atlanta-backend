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

class ClientController extends Controller
{
    private $client;
    private $response;
    private $user;
    private $userService;
    private $clientService;

    public function __construct()
    {
        $this->client = new Client();
        $this->user = new User();
        $this->response = new Response();
        $this->userService = new UserService();
        $this->clientService = new ClientService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['message' => 'Atlanta API', 'status' => 'Connected']);
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
        $userLogged = $this->getAuthUser($request);
        $userType = $userLogged->user_type;
        //var_dump($teste); die();
        
        if ($userType == "D")
        {
            $dealerID = $this->client->getDealerId($userLogged->id);
            $returnUser = $this->userService->create($request);
    
            $returnClient = $this->clientService->create($request, $returnUser->id, $dealerID);
    
            $this->response->setType("S");
            $this->response->setDataSet("user", $returnUser);
            $this->response->setDataSet("client", $returnClient);
            $this->response->setMessages("Created user successfully!");

            return response()->json($this->response->toString(), 200);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

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

    public function getAuthUser(Request $request)
    {
        if (isset($_SERVER['HTTP_TOKEN']))
        {
            $user = JWTAuth::toUser($_SERVER['HTTP_TOKEN']);
        }
        else 
        {
            $user = JWTAuth::toUser($request->token);
        }

        //var_dump($user); die();
        return $user;
    }
}
