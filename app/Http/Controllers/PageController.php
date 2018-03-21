<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Page;
use \App\Response\Response;
use \App\Service\PageService;
use \App\Service\ComponentService;
use \App\Client;
use App\ConfigComponent;

class PageController extends Controller
{
    private $response;
    private $client;
    private $componentPage;
    private $page;
    private $pageService;
    private $componentService;

    public function __construct()
    {
        $this->response = new Response();

        $this->client = new Client();
        $this->pageService = new PageService();
        $this->page = new Page();
        $this->configComponent = new ConfigComponent();

        $this->pageService = new PageService();
        $this->componentService = new ComponentService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userLogged = $this->pageService->getAuthUser($request);
        if($userLogged->user_type != "U")
        {
            $client = $this->client->getClientByUser($userLogged->id);
            $pages = $this->page->getPagesByIdUser($client->id);      
            
            $this->response->setDataSet("Page", $pages);
            $this->response->setType("S");
            $this->response->setMessages("Sucess!");
        }
        
        else 
        {
            $this->response->setType("N");
            $this->response->setMessages("Error!");
        }
        
        return response()->json($this->response->toString());
    }

    /**
     * 
     */
    public function pageClients($id)
    {
        $clients = $this->client->find($id);

        if(!$clients)
        {
            $this->response->setType("N");
            $this->response->setMessages("Client not found!");

            return response()->json($this->response->toString(), 404);
        }
        $pages = $this->page->getPagesByIdUser($id);      
            
        $this->response->setDataSet("Page", $pages);
        $this->response->setType("S");
        $this->response->setMessages("Sucess!");

        return response()->json($this->response->toString());
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
        if ($request->get('client_id'))
        {
            $clientID = $request->get('client_id');
        }
        else
        {
            $userLogged = $this->pageService->getAuthUser($request);
            $client = $this->client->getClientByUser($userLogged->id);
            $clientID = $client->id;
        }
        
        if($clientID != "" || $userLogged->user_type != "D")
        {
            if($client)
            {
                $pageCreate = $this->pageService->create($request, $clientID);
        
                $this->response->setDataSet("Page", $pageCreate);
                $this->response->setType("S");
                $this->response->setMessages("Page created!");
            }
            else 
            {
                $this->response->setType("N");
                $this->response->setMessages("You dont't have permission to create a page!");
            }
        }
        else 
        {
            $this->response->setType("N");
            $this->response->setMessages("You dont't have permission to create a page!");
        }

        return response()->json($this->response->toString());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $userLogged = $this->pageService->getAuthUser($request);
        if($userLogged->user_type != "U")
        {
            $pages = $this->page->find($id);

            if(!$pages)
            {
                $this->response->settype("N");
                $this->response->setMessages("Record not find!");
            }
            else
            {
                $this->response->settype("S");
                $this->response->setMessages("Record not find!");
                $this->response->setDataSet("page", $pages);

            }
        }
        else 
        {
            $this->response->settype("N");
            $this->response->setMessages("User don't have permission!");
        }
        
        return response()->json($this->response->toString());
    }

    /**
     * 
     */
    public function componentsPage(Request $request, $id)
    {
        $userLogged = $this->pageService->getAuthUser($request);
        if($userLogged->user_type != "U")
        {
            $pages = $this->page->find($id);

            if(!$pages)
            {
                $this->response->settype("N");
                $this->response->setMessages("Record not find!");
            }
            else
            {
                $component = $this->page->getComponentsPages($pages->id);
                if($component) {
                    foreach ($component as $key => $value) {
                        $value->configs = $this->page->getConfigComponentPage($value->id);
                    }
                }
                $this->response->settype("S");
                $this->response->setMessages("Sucess!");
                $this->response->setDataSet("Component", $component);

            }
        }
        else 
        {
            $this->response->settype("N");
            $this->response->setMessages("User don't have permission!");
        }

        return response()->json($this->response->toString());
    }

    /**
     * 
     */
    public function storeComponentPage(Request $request, $id)
    {
        $pages = $this->page->find($id);
        if(!$pages)
        {
            $this->response->settype("N");
            $this->response->setMessages("Page not found.");
            return response()->json($this->response->toString(), 404);
        }

        $createComponent = $this->componentService->createComponent($request);
        $createPageComponent = $this->componentService->createConfigComponent($request, $createComponent->id);

        $createPageComponent = $this->pageService->createComponentPage($createComponent->id, $id);

        $this->response->settype("S");
        $this->response->setMessages("Page component created!");
        $this->response->setDataSet("PageComponent", $createPageComponent);

        return response()->json($this->response->toString());
    }

    /**
     * 
     */
    public function updateComponentPage(Request $request, $id, $component_id)
    {
        $pages = $this->page->find($id);
        if(!$pages)
        {
            $this->response->settype("N");
            $this->response->setMessages("Page not found.");
            return response()->json($this->response->toString(), 404);
        }

        $component = $this->configComponent->find($component_id);

        $component->fill($request->all());
        $component->save();

        $this->response->settype("S");
        $this->response->setMessages("Config updated!");
        $this->response->setDataSet("Configs", $component);

        return response()->json($this->response->toString());
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

    /**
     * Display the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showComponentsPage($id)
    {
        $pages = $this->page->find($id);

        if($pages)
        {
            $this->response->setDataSet("Page", $pages);
            $this->response->setType("S");
            $this->response->setMessages("Sucess!");    
        }

        return response()->json($this->response->toString());
    }
}
