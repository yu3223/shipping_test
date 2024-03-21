<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuth implements FilterInterface
{
    use ResponseTrait;

    public function before(RequestInterface $request, $arguments = null)
    {
        $key = getenv('JWT_SECRET');
        $header = $request->getHeaderLine('Authorization');
        $token = null;

        if(!empty($header)){
            if(preg_match('/Bearer\s(\S+)/',$header, $matches)){
                $token = $matches[1];
            }
                
        }

        // check if token is null or empty
        if (is_null($token) || empty($token)) {
            $response = service('response');
            $response->setBody('Access denied');
            $response->setStatusCode(401);
            return $response;
        }

        try {
            $decoded = JWT::decode($token, new Key($key,'HS256'));
        } catch (\Exception $e) {
            //這邊是為了JWT拋出例外時所回傳的reponse，
            //大致上的狀況可以理解成，如果token解析失敗就拋錯
            $response = service('response');
            $response->setBody('Access denied');
            $response->setStatusCode(401);
            return $response;
        }

        session()->set("memberdata",$decoded);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Perform any necessary post-processing here
        return $response;
    }
}

