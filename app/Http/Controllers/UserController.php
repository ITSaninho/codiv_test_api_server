<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\LogoutRequest;
use App\Http\Requests\User\AllowRequest;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public $loginAfterSignUp = true;
 
    public function register(RegisterRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
 
        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }
 
        return response()->json([
            'success' => true,
            'token' => null,
            'data' => $user,
            'message' => null,
        ], 201);
    }
 
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $jwt_token = null;
 
        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'token' => null,
                'data' => null,
                'message' => 'Invalid Email or Password'
            ], 401);
        }
 
        return response()->json([
            'success' => true,
            'token' => $jwt_token,
            'data' => null,
            'message' => "User login success"
        ], 200);
    }
 
    public function logout(LogoutRequest $request)
    { 
        try {
            JWTAuth::invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'token' => null,
                'data' => null,
                'message' => 'User logged out successfully'
            ], 200);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'token' => null,
                'data' => null,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }
 
    public function getAuthUser(AllowRequest $request)
    { 
        $user = JWTAuth::authenticate($request->token);
 
        return response()->json([
            'success' => true,
            'token' => $request->token,
            'data' => $user,
            'message' => 'Get user info'
        ], 200);
    }
}
