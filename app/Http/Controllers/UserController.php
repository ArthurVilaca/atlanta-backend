<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\User;
use JWTAuthException;
use \App\Response\Response;
use \App\Service\UserService;

use App\Http\Controllers\EmailsController;

class UserController extends Controller
{
    private $user;
    private $userService;
    private $response;
    private $emailsController;

    public function __construct(EmailsController $emailsController)
    {
        $this->user = new User();
        $this->response = new Response();
        $this->userService = new UserService();
        $this->emailsController = $emailsController;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $users = $this->user->get();

        $this->response->setDataSet("users", $users);
        $this->response->setType("S");
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
               $this->response->setType("N");
               $this->response->setMessages("invalid_username_or_password");
               return response()->json($this->response->toString(), 422);
           }
        } 
        catch (JWTAuthException $e) 
        {
            $this->response->setType("N");
            $this->response->setMessages("failed_to_create_token");
            return response()->json($this->response->toString(), 500);
        }
        
        $user = JWTAuth::toUser($token);

        if($user->status != "APPROVED")
        {
            $this->response->setType("N");    
            $this->response->setMessages("You don't have permission to do login!");
            return response()->json($this->response->toString(), 500);
        }

        $this->response->setType("S");
        $this->response->setDataSet("token", $token);
        $this->response->setMessages("Login successfully!");
        
        $this->response->setDataSet("name", $user->name);
        $this->response->setDataSet("user_type", $user->user_type);

        return response()->json($this->response->toString(), 200);
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
        $returnUser = $this->userService->create($request);
            
        $this->response->setType("S");
        $this->response->setDataSet("user", $returnUser);
        $this->response->setMessages("Created user successfully!");
        
        return response()->json($this->response->toString(), 200);
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
        $user = $this->user->find($id);
        
        if(!$user) 
        {
            $this->response->setType("N");
            $this->response->setMessages("Record not found!");

            return response()->json($this->response->toString(), 404);
        }

        $user->fill($request->all());
        $user->save();
        $this->response->setType("S");
        $this->response->setDataSet("user", $user);
        $this->response->setMessages("Sucess, user updated!");

        return response()->json($this->response->toString(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->user->find($id);

        if(!$user) 
        {
            $this->response->setType("N");
            $this->response->setMessages("Record not found!");

            return response()->json($this->response->toString(), 404);
        }

        $user->delete();
    }

    /**
     * forgot password user
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(Request $request)
    {
        $userObject = $this->userService->findUserByEmail($request);
        if(!$userObject) {
            $this->response->setType("N");    
            $this->response->setMessages("Usuario não encontrado");
            return response()->json($this->response->toString(), 500);
        }

        $user = $this->user->find($userObject->id);
        $user->token = md5($user->username . date('Y-m-d'));
        $user->save();
        $this->emailsController->send('Ola: ' . $user->name . ' <br>Para cadastrar uma nova senha, clique <a href="http://localhost:3000/#/resetarminhasenha/' . $user->token . '">Aqui</a>!<br>Atenciosamente, Httplay',
            $request->get('email'),
            '[HTTPLAY] - Esqueci minha senha'
        );

        $this->response->setType("S");
        $this->response->setMessages("Email enviado com sucesso!");

        return response()->json($this->response->toString(), 200);
    }

    /**
     * reset password user
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {
        $userObject = $this->userService->findUserByToken($request);
        if(!$userObject) {
            $this->response->setType("N");    
            $this->response->setMessages("Usuario não encontrado");
            return response()->json($this->response->toString(), 500);
        }

        $user = $this->user->find($userObject->id);
        if($userObject->token !== $request->get('token')) {
            $this->response->setType("N");    
            $this->response->setMessages("Dados Invalidos!");
            return response()->json($this->response->toString(), 500);
        }

        $user->password = bcrypt($request->get('password'));
        $user->save();

        $this->response->setType("S");
        $this->response->setMessages("Alteração realizada com sucesso!");

        return response()->json($this->response->toString(), 200);
    }
}
