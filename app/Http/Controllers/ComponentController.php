<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use JWTAuthException;
use \App\Component;
use \App\ConfigComponent;
use \App\Response\Response;
use \App\Service\ComponentService;

class ComponentController extends Controller
{
    private $response;
    private $component;
    private $configComponent;
    private $componentService;

    public function __construct()
    {
        $this->response = new Response();
        $this->component = new Component();
        $this->configComponent = new ConfigComponent();
        $this->componentService = new ComponentService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $userLogged = $this->componentService->getAuthUser($request);

        if($userLogged->user_type != "U")
        {
            try
            {
                $componentCreate = $this->componentService->createComponent($request);
                $configComponentCreate = $this->componentService->createConfigComponent($request, $componentCreate->id);        
                
                $componentCreate->config = $configComponentCreate;
                $this->response->setType("S");
                $this->response->setDataSet("user", $componentCreate);
                $this->response->setMessages("Created component successfully!");
            }
            catch(Exception $e)
            {
                $this->response->setType("N");
                $this->response->setMessages("Failed to create a component!");
                return response()->json($this->response->toString(), 200);
            }
            
            return response()->json($this->response->toString(), 200);
        }
        else 
        {
            $this->response->setType("N");
            $this->response->setMessages("You don't have permission to create a component!");
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

    /**
     * Metodo para saber o usuario logado
     */
    private function getAuthUser(Request $request)
    {
        if (isset($_SERVER['HTTP_TOKEN']))
        {
            $user = JWTAuth::toUser($_SERVER['HTTP_TOKEN']);
        }
        else 
        {
            $user = JWTAuth::toUser($request->token);
        }

        return $user;
    }
}
