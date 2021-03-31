<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{
    public function register(Request $request){
        $validatedData = $request->validate([
            'name' => 'required |max:55',
            'email'=> 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);
        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accesToken;

        return response([ 'user'=> $user, 'access_token' => $accessToken]);

    }

    public function login(Resquest $request){

        $loginData = $request->validate([
            'email' => 'email|required',
            'password'=> 'required'
        ]);

        if(!auth()->attempt($loginData)) {
            return response(['message' => 'invalid credentials']);
        }

        $accesToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user(), 'accessToken' => $accessToken]);

    }
    //validação com passport utilizando token
}
