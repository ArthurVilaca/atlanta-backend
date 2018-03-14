<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Response\Response;
use \App\Http\Controllers\UserController;
use App\Client;
use App\User;
use JWTAuthException;
use JWTAuth;

class ClientController extends Controller
{
    private $client;
    private $response;
    private $user;
    
    public function __construct()
    {
        $this->client = new Client();
        $this->user = new User();
        $this->response = new Response();
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
        $userID = $this->getAuthUser($request);
        $dealerID = $this->client->getDealerId($userID);

        $returnUser = $this->user->create([
            'username' => $request->get('username'),
            'name' => $request->get('name'),
            'password' => bcrypt($request->get('password'))
        ]);

        $returnClient = $this->client->create([
            'registration_code' => $request->get('registration_code'),
            'company_branch' => $request->get('company_branch'),
            'sale_plan' => $request->get('sale_plan'),
            'user_id' => $userID,
            'dealer_id' => $dealerID,
        ]);

        $this->response->setType("S");
        $this->response->setDataSet("user", $returnUser);
        $this->response->setDataSet("client", $returnClient);
        $this->response->setMessages("Created user successfully!");
        
        return response()->json($this->response->toString(), 200);
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
        return $user->id;
    }
}
