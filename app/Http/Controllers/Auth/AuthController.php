<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Routing\Controllers\Middleware;


class AuthController extends Controller implements \Illuminate\Routing\Controllers\HasMiddleware
{
    public static function middleware():array
    {
        return [
            new Middleware(middleware:'auth:api',except: ['login']),
        ];
    }
    public function __construct(){
        $this->middleware();
    }
    /**
     * Summary of login
     * @param \App\Http\Requests\LoginRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid Credentials'], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'token' => $token
        ], 200);
    }
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['status' => 'success', 'message' => 'User logged out successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to logout, token invalid or missing'], 401);
        }
    }
    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json([
                'status' => 'success',
                'email' => $user->email,
                'role'=>$user->getRoleNames()->first()
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to authenticate'], 401);
        }
    }
}
