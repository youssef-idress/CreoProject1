<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class SessionController extends Controller
{
    public function login(Request $request){
            $validate = Validator::make($request->all(),[
                'email' => 'required|email',
                'password' => 'required'
            ]);
            //checks if the user exists (if they are not provided)
            if($validate->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validate->errors()
                ],401);
            }
            //checks if the user attempting to login has a user or not (found in DB or not)
            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email or Password are incorrect',
                ], 401);
            }

            $user = User::where('email', $request -> email) -> first();
            return response() ->json([
                'status' => true,
                'message' => 'Logged in successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);  
        }

    public function logout(Request $request){
    $request->user()->tokens()->delete();
    return response()->json([
            'status' => true,
            'message' => 'Logout Successful',
        ], 200);
    }
}
