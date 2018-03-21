<?php 
namespace App\Service;

use Illuminate\Http\Request;
use App\Dealer;

class DealerService extends Service
{
    private $dealer;

    public function __construct()
    {
        $this->dealer = new Dealer();
    }

    public function create(Request $request, $userID)
    {
        $returnDealer = $this->dealer->create([
            'registration_code' => $request->get('registration_code'),
            'user_id' => $userID,
        ]);

        return $returnDealer;
    }
}
?>