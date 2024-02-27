<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function register(Request $request){
    $registerUserData = $request->validate([
        'name'=>'required|string',
        'email'=>'required|string|email|unique:users',
        'password'=>'required|min:8'
    ]);
    $user = User::create([
        'name' => $registerUserData['name'],
        'email' => $registerUserData['email'],
        'password' => Hash::make($registerUserData['password']),
    ]);
    return response()->json([
        'message' => 'User Created ',
    ]);
}


 public function login(Request $request){
        $loginUserData = $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|min:8'
        ]);
        $user = User::where('email',$loginUserData['email'])->first();
        if(!$user || !Hash::check($loginUserData['password'],$user->password)){
            return response()->json([
                'message' => 'Invalid Credentials'
            ],401);
        }
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
        return response()->json([
            'access_token' => $token,
        ]);
    }

    public function logout(){
        if(Auth::check()){
        auth()->user()->tokens()->delete();
         return response()->json([
            'status' => '200',
            'message' => 'You have been logged out'
        ], 200);
        }else{
             return response()->json([
            'status' => '404',
            'message' => 'please login first'
        ], 404);
        }

       
    }

}
