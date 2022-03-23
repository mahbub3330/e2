<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if (! Auth::attempt($validator)){
            return response()->json(['message' => 'Invalid Login Credentials']);
        }
        $tokenResult = Auth::user()->createToken('Laravel Password Grant Client');
        $token = Auth::user()->createToken('Laravel Password Grant Client')->accessToken;
        $user = Auth::user();

        $response = [
            'access_token' => $token,
            'user' => $user,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
        ];

        return response()->json($response);
    }


    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        try {
            $user = Auth::user();
            $tokenResult = $user->createToken('Laravel Password Grant Client');
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            $response = [
                'token' => $token,
                'user' => $user,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ];
            return response($response, 200);
        } catch (\Exception $e) {
            $response = ["message" => 'User does not exist'];
            return response($response, 422);
        }
    }

}
