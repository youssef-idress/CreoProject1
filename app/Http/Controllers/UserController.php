<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{     
    public function createUser(Request $request){
        // This function creates a user and saves it in the DB
            $Vuser = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).*$/|confirmed',
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
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
    }

    public function read(Request $request){
        $userRead = auth()->user();
        if($userRead){
            $name = $userRead->name;
            $email = $userRead->email;

            return response()->json([
                'status' => true,
                'data' => [
                    'name' => $name,
                    'email' => $email,
                ],
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'No user to return',
            ]);
        }
    }

    public function Update(Request $request){
        $user = auth()->user();

        $request->validate([
            'name' => 'string',
            'email' => ['email', Rule::unique('users')->ignore(auth()->user()-id)],
            'password' => 'min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).*$/',
        ]);

        if($request->has('name')){
            $user->name = $request->input('name');
        }

        if($request->has('email')){
            $user->email = $request->input('email');
        }

        if($request->has('password')){
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User has been updated',
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function Delete(Request $request){
        $userDeleted = auth()->user();

        if($user){
            $userDeleted->delete();

            return response()->json([
                'status' => true,
                'message' => 'User has been deleted',
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ]);
        }
    }
}