<?php 
namespace App\Service;
use Illuminate\Http\Request;
use App\User;

class UserService extends Service
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function create(Request $request)
    {
        $returnUser = $this->user->create([
            'username' => $request->get('username'),
            'name' => $request->get('name'),
            'password' => bcrypt($request->get('password')),
            'user_type' => $request->get('user_type'),
            'phone' => $request->get('phone'),
            'email' => $request->get('email'),
            'adress' => $request->get('adress'),
            'adress_number' => $request->get('adress_number'),
            'adress_complement' => $request->get('adress_complement'),
            'adress_district' => $request->get('adress_district'),
            'zip_code' => $request->get('zip_code'),
            'city' => $request->get('city'),
            'state' => $request->get('state'),
        ]);

        return $returnUser;
    }

    public function findUserByEmail(Request $request)
    {
        $returnUser = $this->user->findUserByEmail($request->get('email'));
        return $returnUser;
    }

    public function findUserByToken(Request $request)
    {
        $returnUser = $this->user->findUserByToken($request->get('token'));
        return $returnUser;
    }
}
?>