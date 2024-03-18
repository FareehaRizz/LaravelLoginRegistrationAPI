<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
    public function register(Request $request){
        $validator = Validator::make($request->all() , [
            'name'=> 'required|string|min:2|max:100',
            'email'=> 'required|string|email|max:100|unique:users',
            'password'=>'required|string|min:6|confirmed'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
       $user = User::create([
         'name'=>$request->name,
         'email'=>$request->email,
         'password'=>Hash::make($request->password)

       ]);
        return response()->json([
         'message' => 'User Registered Successfully',
         'user'=> $user
        ]);
    }

    //method for user login
    public function login(Request $request){
        $validator = Validator::make($request->all() , [
            'email'=> 'required|string|email',
            'password'=>'required|string|min:6'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        //checking if the token is being passed or not
        if(!$token = auth()->attempt($validator->validated()))
        {
            return response()->json(['error'=>'Unauthorized']);
        }
        return $this->respondWithToken($token);
        
    }
    //but if the user is authorized then we will generate and send the token in a strustured way
        //only the class which inherits this function will be able to run it
        protected function respondWithToken($token)
        {
            return response()->json([
                'access_token'=>$token,
                'token_type'=>'bearer',
                'expires_in'=>auth()->factory()->getTTL()*60
            ]);
        }
        public function profile()
        {
            return response()->json(auth()->user());
        }

        public function refresh()
        {
            return $this->respondWithToken(auth()->refresh());
        }

        public function logout()
        {
            auth()->logout();

            return response()->json(['message'=>'User Successfully Logged out!']);
        }
}
