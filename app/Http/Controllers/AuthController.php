<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthSigninRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //LOGIN route
    public function login(AuthLoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(["error" => "L'adresse email fournise ou le mot de passe est incorrecte !"], 401);
        }

        $user->tokens()->where('tokenable_id', $user->id)->delete();

        $random = Str::random(40);
        $token = $user->createToken($random)->plainTextToken;
        return response()->json([
            'token' => $token,
            'name' => $user->name,
            'email' => $user->email
        ], 200);
    }

    //SIGNIN route
    public function signin(AuthSigninRequest $request)
    {
        $exist = User::where('email', $request->email)->exists();
        if($exist)
        {
            return response()->json(["error" => "Adresse email invalid !"], 409);
        }

        $user = User::create([
            'name' =>$request->name,
            'email' =>$request->email,
            'password' => Hash::make($request->password)
        ]);

        $random = Str::random(40);
        $token = $user->createToken($random)->plainTextToken;
        return response()->json([
            'token' => $token,
            'name' => $user->name,
            'email' => $user->email
        ], 200);
    }

    //LOGOUT route
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['success' => "Vous avez été deconnecté !"], 204);
    }
}
