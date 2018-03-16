<?php 
namespace App\Service;

use Illuminate\Http\Request;
use App\Page;

class PageService extends Service
{
    private $page;

    public function __construct()
    {
        $this->page = new Page();
    }
}