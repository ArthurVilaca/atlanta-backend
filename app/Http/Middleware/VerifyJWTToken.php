<?php
namespace App\Http\Middleware;
use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use \App\Response\Response;

class VerifyJWTToken
{
    private $response;


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->response = new Response();
        /**
         * Gambiarra a ser modificada
         * Valida o token vindo do header ou pela url
         */        
        if (isset($_SERVER['HTTP_TOKEN']))
        {
            $token = $_SERVER['HTTP_TOKEN'];
        }
        else 
        {
            $token = $request->input('token');
        }

        try
        {
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e) 
        {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) 
            {
                $this->response->setType("N");
                $this->response->setMessages('token_expired');
                return response()->json($this->response->toString(), $e->getStatusCode());
            }
            else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) 
            {
                $this->response->setType("N");
                $this->response->setMessages('token_invalid');
                return response()->json($this->response->toString(), $e->getStatusCode());
            }
            else
            {
                $this->response->setType("N");
                $this->response->setMessages('Token is required');
                return response()->json($this->response->toString());
            }
        }
       return $next($request);
    }
}