<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    
    public function create(Request $request)
    {
        $validation = $this->validateUser($request->input());

        if($validation){
            return response()->json([
                'status'=> false,
                'errors'=> $validation
            ], 400);
        }else{
            $user = User::create([
                'name'=> $request->input('name'),
                'email'=> $request->input('email'),
                'password'=> Hash::make( $request->input('password'))
            ]);

            return response()->json([
                'status'=> true,
                'message'=>'User Created Succefully',
                'token'=> $user->createToken('API TOKEN')->plainTextToken
            ], 200);
        }

    }

    public function login(Request $request){
        $validation = Validator::make($request->input(), [
            'email'=> 'required|string|email',
            'password'=> 'required|string',
        ]);

        if($validation->fails()){
            return response()->json([
                'status'=> false,
                'errors'=> $validation
            ], 400);
        }

        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'status'=> false,
                'errors'=> ['Unauthorized']
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status'=> true,
            'message'=> 'User Logged Successfully',
            'data' => $user,
            'token'=> $user->createToken('API TOKEN')->plainTextToken
        ], 200);

    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status'=> true,
            'message'=> 'User Logout Successfully'
        ], 200);
    }



    public function validateUser($params){
        $validation = Validator::make($params, [
            'name'=> 'required|string|min:2|max:100',
            'email'=> 'required|string|email|unique:users,email|min:2|max:100',
            'password'=> 'required|string|min:8'
        ]);

        if($validation->fails()){
            return $validation->errors();

        }else{
            return false;
        }
    }
}
