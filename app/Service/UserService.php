<?php 
namespace App\Service;
use Illuminate\Http\Request;
use App\User;

class UserService
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
        ]);

        return $returnUser;
    }
}
?>