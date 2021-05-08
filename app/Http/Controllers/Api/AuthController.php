<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\Http\Resources\UserResource;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use CodeShopping\Http\Controllers\Controller;


class AuthController extends Controller
{
    use AuthenticatesUsers;
    public function login(Request $request) {
        $this->validateLogin($request);
        $credentials = $this->credentials($request);
        $token = \JWTAuth::attempt($credentials);
        return $token ?
            ['token' => $token] :
            response()->json([
                'error' => \Lang::get('auth.failed')
            ], 400);
    }

    function logout()
    {
        \Auth::guard('api')->logout();
        return response()->json([], 201);
    }

    function me()
    {
        $user = \Auth::guard('api')->user();
        return new UserResource($user);
    }
    function refresh()
    {
        $token = \Auth::guard('api')->refresh();
        return ['token' => $token];
    }
}
