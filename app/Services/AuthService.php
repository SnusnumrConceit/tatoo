<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 11.08.20
 * Time: 0:30
 */

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\Admin\User\UserVuex;

class AuthService
{
    protected $token;
    
    /**
     * JWT Authorization
     *
     * @param array $credentials
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function JWTAuthorize(array $credentials)
    {
        if (! $token = JWTAuth::attempt($credentials)) {
            throw new \Exception('Неверные данные');
        }
        
        $this->token = $token;
    }
    
    /**
     * HTTP Response method with payload:
     * - user
     * - token
     *
     * @param $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function authorizationResponse($user)
    {
        return response()->json([
            'user'  => new UserVuex($user),
            'token' => $this->token
        ], 200);
    }
}