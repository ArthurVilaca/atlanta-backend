<?php 
namespace App\Service;

use Illuminate\Http\Request;
use App\Page;
use App\Client;
use App\ComponentPage;

class PageService extends Service
{
    private $page;
    private $client;
    private $componentPage;

    public function __construct()
    {
        $this->page = new Page();
        $this->client = new Client();
        $this->componentPage = new ComponentPage();
    }

    public function create(Request $request, $clientID)
    {
        $pageCreate = $this->page->create([
            'name' => $request->get('name'),
            'url' => $request->get('url'),
            'file' => $request->get('file'),
            'status' => $request->get('status'),
            'client_id' => $clientID,
        ]);

        return $pageCreate;
    }

    public function createComponentPage($componentID, $pageID)
    {
        $componentPageCreate = $this->componentPage->create([
            'component_id' => $componentID,
            'page_id' => $pageID
        ]);

        return $componentPageCreate;
    }

    public function getClient($userLoggedID)
    {
        $client = $this->client->getClientByUser($userLoggedID);
        return $client;   
    }
}