<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Auth;
use Hash;
use App\User;

class AuthController extends Controller
{
    public function login(Request $request) 
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('leapinglizards')->accessToken;
                $success['token'] = $token;
                $success['user'] = new UserResource($user);
                return response()->json($success, 200);
            } else {
                $success['error'] = "Login Credentials are not valid";
                return response()->json($success, 422);
            }

        } else {
            $success['error'] = 'Login Credentials are not valid';
            return response()->json($success, 422);
        }
    }

    public function me() 
    {
        $user = Auth::user();
        return response()->json(new UserResource(Auth::user()));
    }
}
