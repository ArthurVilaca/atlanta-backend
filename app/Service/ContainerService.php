<?php 
namespace App\Service;

use Illuminate\Http\Request;
use App\Tasks;

class ContainerService extends Service
{
    private $container;

    public function __construct()
    {
        $this->tasks = new Tasks();
    }

    public function get()

    {
        $tasks = $this->tasks->get();
        return $tasks;
    }

    public function create(Request $request)
    {
        
    }
}
?>