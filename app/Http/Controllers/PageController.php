<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Page;
use \App\Response\Response;
use \App\Service\PageService;
use \App\Service\ComponentService;

class PageController extends Controller
{
    private $response;
    private $page;
    private $pageService;
    private $componentService;

    public function __construct()
    {
        $this->response = new Response();
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
            $pages = $this->page->getPagesByIdUser($userLogged->id);      
                  
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
        //
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
