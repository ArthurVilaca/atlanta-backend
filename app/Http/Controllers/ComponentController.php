<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use JWTAuthException;
use \App\Component;
use \App\ComponentPage;
use \App\ConfigComponent;
use \App\Response\Response;
use \App\Service\ComponentService;

class ComponentController extends Controller
{
    private $response;
    private $component;
    private $componentPage;
    private $configComponent;
    private $componentService;

    public function __construct()
    {
        $this->response = new Response();
        $this->component = new Component();
        $this->componentPage = new ComponentPage();
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
        $getComponent = $this->component->find($id);

        if(!$getComponent)
        {
            $this->response->setType("N");
            $this->response->setMessages("Record not found!");

            return response()->json($this->response->toString(), 404);
        }

        $getConfigComponent = $this->configComponent->getConfigComponentPage($getComponent->id);
        if($getConfigComponent)
        {
            $getComponent->config = $getConfigComponent;
        }

        $this->response->setType("S");
        $this->response->setDataSet("Component", $getComponent);
        $this->response->setMessages("Sucess!");

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
        $getComponent = $this->component->find($id);

        if(!$getComponent)
        {
            $this->response->setType("N");
            $this->response->setMessages("Record not found!");

            return response()->json($this->response->toString(), 404);
        }
        $getComponent->fill($request->all());
        $getComponent->save();

        $getConfigComponent = $this->configComponent->getConfigComponentPage($getComponent->id);
        if($getConfigComponent)
        {
            //Transformação em objeto do model
            $getConfigComponent = $this->configComponent->find($getConfigComponent->id);
            $getConfigComponent->fill($request->all());
            $getConfigComponent->save();
            $getComponent->config = $getConfigComponent;
        }

        $this->response->setType("S");
        $this->response->setDataSet("Component", $getComponent);
        $this->response->setMessages("Sucess, component updated!");

        return response()->json($this->response->toString(), 200);
    }

    /**
     * Remove the specified resource from storage.     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $getComponent = $this->component->find($id);

        if(!$getComponent) 
        {
            $this->response->setType("N");
            $this->response->setMessages("Record not found!");

            return response()->json($this->response->toString(), 404);
        }

        $getComponentPage = $this->componentPage->getPageComponent($getComponent->id);
        if($getComponentPage)
        {
            //Transformação em objeto do model
            $getComponentPage = $this->componentPage->find($getComponentPage->id);
            $getComponentPage->delete();
        }

        $getConfigComponent = $this->configComponent->getConfigComponentPage($getComponent->id);
        if($getConfigComponent)
        {
            //Transformação em objeto do model
            $getConfigComponent = $this->configComponent->find($getConfigComponent->id);
            $getConfigComponent->delete();  
        }

        $getComponent->delete();
    }
}
