<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\User;
use JWTAuthException;
use \App\Response\Response;

class UserController extends Controller
{
    private $user;
    private $response;
    
    public function __construct()
    {
        $this->user = new User();
        $this->response = new Response();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $users = User::get();

        $this->response->setDataSet("user", $users);
        $this->response->setMessages("Sucess!");

        return response()->json($this->response->toString());
    }

    /**
     * Login user
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $token = null;
        try 
        {
           if (!$token = JWTAuth::attempt($credentials)) 
           {
                return response()->json(['invalid_username_or_password'], 422);
           }
        } 
        catch (JWTAuthException $e) 
        {
            return response()->json(['failed_to_create_token'], 500);
        }
        $this->response->setDataSet("token", $token);
        $this->response->setMessages("Login successfully!");
        
        $user = JWTAuth::toUser($token);        
        $this->response->setDataSet("name", $user->name);

        return response()->json($this->response->toString());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //echo $request->get('username'); die();
        $returnUser = $this->user->create([
            'username' => $request->get('username'),
            'name' => $request->get('name'),
            'password' => bcrypt($request->get('password'))
        ]);

        $this->response->setDataSet("user", $returnUser);
        $this->response->setMessages("Created user successfully!");
        
        return response()->json($this->response->toString());
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
    {
        //
    }

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
