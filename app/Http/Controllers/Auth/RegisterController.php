<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\Auth\RegistrationRequest;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    
    protected $authService;
    
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
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return mixed
     */
    protected function create(array $data)
    {
        $data['birthday'] = Carbon::parse($data['birthday'])->format('Y-m-d');
        $data['password'] = bcrypt($data['password']);
        
        return User::create($data);
    }
    
    /**
     * Registry user in the system
     *
     * @param RegistrationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function registry(RegistrationRequest $request)
    {
        $user = $this->create($request->validated());
    
        $this->authService->JWTAuthorize($credentials = $request->only('email', 'password'));
    
        auth()->attempt($credentials, true);
        
        return $this->authService->authorizationResponse($user);
    }
}
