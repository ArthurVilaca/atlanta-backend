<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Page;
use \App\Response\Response;
use \App\Service\PageService;
use \App\Service\ComponentService;
use App\Component;
use App\ConfigComponent;

class DealerPageController extends Controller
{
    private $response;
    private $page;
    private $pageService;
    private $componentService;
    private $component;
    private $configComponent;

    public function __construct()
    {
        $this->response = new Response();

        $this->pageService = new PageService();
        $this->page = new Page();
        $this->component = new Component();
        $this->configComponent = new ConfigComponent();

        $this->pageService = new PageService();
        $this->componentService = new ComponentService();
    }

    /**
     * 
     */
    public function dealerComponentsPage(Request $request)
    {
        $userLogged = $this->pageService->getAuthUser($request);
        if($userLogged->user_type != "U")
        {
            $pages = $this->page->findPageByDealer($userLogged->id);
            if(!$pages) {
                $pages = $this->page->create([
                    'name' => $userLogged->username,
                    'url' => $userLogged->username,
                    'file' => 'file',
                    'status' => 'Publicado',
                    'dealers_id' => $userLogged->id,
                ]);
            }

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
        else {
            $this->response->settype("N");
            $this->response->setMessages("User don't have permission!");
        }

        return response()->json($this->response->toString());
    }

    /**
     * 
     */
    public function dealerStoreComponentPage(Request $request)
    {
        $userLogged = $this->pageService->getAuthUser($request);
        $pages = $this->page->findPageByDealer($userLogged->id);
        if(!$pages) {
            $pages = $this->page->create([
                'name' => $userLogged->username,
                'url' => $userLogged->username,
                'file' => 'file',
                'status' => 'Publicado',
                'dealers_id' => $userLogged->id,
            ]);
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
    public function dealerUpdateComponentPage(Request $request, $component_id)
    {
        $userLogged = $this->pageService->getAuthUser($request);
        $pages = $this->page->findPageByDealer($userLogged->id);
        if(!$pages) {
            $pages = $this->page->create([
                'name' => $userLogged->username,
                'url' => $userLogged->username,
                'file' => 'file',
                'status' => 'Publicado',
                'dealers_id' => $userLogged->id,
            ]);
        }

        $component = $this->configComponent->find($component_id);

        $component->fill($request->all());
        $component->save();

        $this->response->settype("S");
        $this->response->setMessages("Config updated!");
        $this->response->setDataSet("Configs", $component);

        return response()->json($this->response->toString());
    }
}
