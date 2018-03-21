<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuthException;
use JWTAuth;
use \App\Dealer;
use \App\User;
use \App\Response\Response;
use \App\Service\UserService;
use \App\Service\DealerService;

class DealerController extends Controller
{
    private $dealer;
    private $response;
    private $user;
    private $userService;
    private $dealerService;
    
    public function __construct()
    {
        $this->dealer = new Dealer();
        $this->user = new User();
        $this->response = new Response();
        $this->dealerService = new DealerService();
        $this->userService = new UserService();
    }
    /**
     * Display a listing of the resource.
     * @param $request se neceesário o token 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $this->dealerService->getAuthUser($request);

        $dealer = $this->dealer->getDealerByUserId($user->id);

        $user->dealer = $dealer;

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
        $returnUser = $this->userService->create($request);
        $returnDealer = $this->dealerService->create($request, $returnUser->id);

        $this->response->setType("S");
        $this->response->setDataSet("user", $returnUser);
        $this->response->setDataSet("dealer", $returnDealer);
        $this->response->setMessages("Created dealer successfully!");
        
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
        $dealer = $this->dealer->find($id);
        
        if(!$dealer)
        {
            $this->response->settype("N");
            $this->response->setMessages("Record not find!");

            return response()->json($this->response->toString(), 404);
        }

        $user = $this->user->find($dealer->user_id);

        if(!$user) 
        {
            $this->response->settype("N");
            $this->response->setMessages("Record not find!");

            return response()->json($this->respose->toString(), 404);
        }

        $this->response->setType("S");
        $this->response->setDataSet("user", $user);
        $this->response->setDataSet("dealer", $dealer);
        $this->response->setMessages("Sucess, dealer updated!");

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
        $dealer = $this->dealer->find($id);
        $user = $this->user->find($dealer->user_id);

        if(!$user) 
        {
            $this->response->settype("N");
            $this->response->setMessages("Record not find!");

            return response()->json($this->response->toString(), 404);
        }

        $dealer->fill($request->all());
        $dealer->save();

        $user->fill($request->all());
        $user->save();

        $this->response->setType("S");
        $this->response->setDataSet("user", $user);
        $this->response->setDataSet("dealer", $dealer);
        $this->response->setMessages("Sucess, dealer updated!");

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
        $dealer = $this->dealer->find($id);
        $user = $this->user->find($dealer->user_id);

        if(!$user) 
        {
            $this->response->settype("N");
            $this->response->setMessages("Record not find!");

            return response()->json($this->response->toString(), 404);
        }

        $dealer->delete();
        $user->delete();
    }   
}
