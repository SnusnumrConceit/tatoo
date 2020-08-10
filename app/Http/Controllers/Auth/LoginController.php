<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    
    private $authService;

    /**
     * Create a new controller instance.
     *
     * @param AuthService $authService
     *
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    
    /**
     * Login user
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function login(LoginRequest $request)
    {
        $this->authService->JWTAuthorize($credentials = $request->only('email', 'password'));
    
        auth()->attempt($credentials, true);
    
        return $this->authService->authorizationResponse(auth()->user());
    }
    
    /**
     * Logout user
     *
     * @return void
     */
    public function logout()
    {
        auth()->logout();
    }
}
