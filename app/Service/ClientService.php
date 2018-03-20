<?php 
namespace App\Service;

use Illuminate\Http\Request;
use App\Client;

class ClientService extends Service
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function create(Request $request, $userID, $dealerID)
    {
        $returnClient = $this->client->create([
            'registration_code' => $request->get('registration_code'),
            'company_branch' => $request->get('company_branch'),
            'sale_plan' => $request->get('sale_plan'),
            'user_id' => $userID,
            'dealer_id' => $dealerID,
        ]);

        return $returnClient;
    }
}
?>