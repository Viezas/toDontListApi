<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthSigninRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    
    //ME route
    public function me()
    {
        $user = User::where('email', Auth::user()->email)->get();
        return response()->json([
            'id' => $user[0]->id,
            'name' => $user[0]->name,
            'email' => $user[0]->email,
            'created_at' => $user[0]->created_at,
            'updated_at' => $user[0]->updated_at
        ], 200);
    }
}
