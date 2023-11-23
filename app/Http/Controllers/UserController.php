<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        // This function creates a user and saves it in the DB
        try {
            $Vuser = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])|confirmed/',
            ]);

            // Just to check whether the parameters provided are actually unique or provided
            if ($Vuser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $Vuser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // This will return the user indicating that the user has been
            // created. In addition, it creates a token for that user
            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken()
            ], 200);
        } catch (\Throwable $error) {
            return response()->json([
                'status' => false,
                'message' => $error->getMessage()
            ], 500);
        }
    }


    public function login(Request $request){
        try{
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
                'token' => $user->createToken("API TOKEN")-plainTextToken()
            ], 200);  
        }
        catch(\Throwable $err){
            \Log::error($err);
            return response()->json([
                'status' => false,
                'message' => 'An error occured,please try again',
            ],500);
        }
    }

    public function logout(Request $request){
        try{
            $request->user()->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logout Successful',
            ], 200);
        }
        catch(\Throwable $err){
            return response()->json([
                'status' => false,
                'message' => 'There was an error, please try again later: ' . $err->getMessage(),
            ], 500);
        }
    }
}