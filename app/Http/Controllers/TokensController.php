<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Http\Request;
//Librerie of Facade-Auth-General
use Tymon\JWTAuth\Facades\JWTAuth;
//Libreries of exceptions.
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

use Illuminate\Support\Facades\Auth;
class TokensController extends Controller
{
    public function login(Request $request){
        $credentials=$request->only('email','password');
        // return response()->json([ 'credentials'=>$credentials]);
        $validator=Validator::make($credentials,[
            'email'=>'required|email',
            'password'=>'required'    
        ]);
        if($validator->fails()){
            return response()->json([
                'success'=>'false',
                'message'=>'Datos Incorrectos',
                'errors'=>$validator->errors()
            ],422);
        }
        $token = JWTAuth::attempt($credentials);
        if ($token) {

            return response()->json([
                'token' => $token,
                'user' => User::where('email', $credentials['email'])->get()->first()
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'code' => 401,
                'message' => 'Wrong credentials',
                'errors' => $validator->errors()], 401);
        }
    }

    
    public function refreshToken(){

        $token = JWTAuth::getToken();
        try {
            $token = JWTAuth::refresh($token);
            return response()->json(['success' => true, 'token' => $token], 200);
        } catch (TokenExpiredException $ex) {
            // We were unable to refresh the token, our user needs to login again
            return response()->json([
                'code' => 422, 'success' => false, 
                'message' => 'Need to login again, please (expired)!'
            ]);
        } catch (TokenBlacklistedException $ex) {
            // Blacklisted token
            return response()->json([
                'code' => 422, 'success' => false,
                 'message' => 'Need to login again, please (blacklisted)!'
            ], 422);
        }
    }

    public function logout()
    {
        //  $this->validate($request, ['token' => 'required']);
        $token = JWTAuth::getToken();

        try {
            $token = JWTAuth::invalidate($token);
            return response()->json([
                'code' => 5, 'success' => true, 'message' => "You have successfully logged out."
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'code' => 6, 'success' => false, 'message' => 'Failed to logout, please try again.'
            ], 422);
        }

    }

    public function getUserAuthenticated(){
        $user = Auth::user();
        return response()->json($user, 200);
    }
}
