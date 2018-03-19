<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Page;
use \App\Response\Response;
use \App\Service\PageService;
use \App\Service\ComponentService;
use \App\Client;

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
            
            foreach ($pages as $page) 
            {
                $page->component = $this->page->getComponentsPages($page->id);
                $page->component->configs = $this->page->getConfigComponentPage($page->component->id);
            }

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
        $userLogged = $this->pageService->getAuthUser($request);
        if($userLogged->user_type == "C")
        {
            $client = $this->client->getClientByUser($userLogged->id);
            //var_dump($client); die();
            if($client)
            {
                $pageCreate = $this->pageService->create($request, $client->id, 1);
        
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
        
        return response()->json($this->response->toString(), 404);
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
                $component->configs = $this->page->getConfigComponentPage($component->id);
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

        return response()->json($this->response->toString(), 404);
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
