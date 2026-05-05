<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    public function login(Request $request){
        $credentials = $request->only(['email', 'password']);

        if(!$token = auth('api')->attempt($credentials)){
            return response()->json([
                'status' => false,
                'message' => 'Login Invalid!',
                'error' => 'Unauthorized'
            ], 401);
        }

        // return $this->respondWithToken($token);
        return response()->json([
            'status' => true,
            'message' => 'Succefully logged in',
            'data' => [
                'token' => $token
            ]
        ]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Succesfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    // protected function respondWithToken($token)
    // {
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type'=> 'bearer',
    //         'expires_in' => auth('api')->factory()->getTTL() * 60
    //     ]);
    // }
}
