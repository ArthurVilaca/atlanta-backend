<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuthException;
use JWTAuth;
use \App\Response\Response;
use \App\Service\ContainerService;

class ContainerController extends Controller
{
    private $containerService;
    private $response;
    
    public function __construct()
    {
        $this->response = new Response();
        $this->containerService = new ContainerService();
    }
    /**
     * Display a listing of the resource.
     * @param $request se neceesário o token 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $container = $this->containerService->get();

        $this->response->setType("S");
        $this->response->setDataSet("containers", $container);
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
        
    }   
}
