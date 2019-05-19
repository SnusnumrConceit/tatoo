<?php

namespace App\Http\Controllers\Auth;

use App\Events\WriteAudit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Admin\User\UserVuex;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Auth;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                throw new JWTException('Неверные данные');
            }
            Auth::attempt($credentials, true);
            $this->makeLog(auth()->user(), 17, 1);
            $user = User::with('role')
                ->findOrFail(auth()->id());
            return response()->json([
                'user' => new UserVuex($user),
                'token' => $token,
                'csrf_token' => csrf_token()
            ], 200);
        } catch (JWTException $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function logout()
    {
        try {
            $user = auth()->user();
            auth()->logout();
            $this->makeLog($user, 18, 1);
            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function makeLog($subject, $type, $status)
    {
        switch ($status) {
            case 1: $status = json_encode((object)['status' => 'success']); break;
            case 2: $status = json_encode((object)['status' => 'error']); break;
            default: break;
        }
        $subject = json_encode((object)[
            'id' => ($type !== 15) ? $subject->id : null,
            'type' => 'user',
            'email' => $subject->email
        ]);
        event(new WriteAudit($subject, $type, $status));
    }
}
