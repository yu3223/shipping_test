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

        // 這邊邏輯比較簡單，你等等可以看下
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

// 備註 : 
// 先把filter當作是守門員，它必須抵擋非法請求，所以只要遇到不是我們想要的請求，我們就把它擋掉
// 然後回傳response回去，因為這是一個HTTP交互~