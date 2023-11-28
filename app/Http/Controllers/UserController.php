<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View; 
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Input; 
use Illuminate\Support\Facades\Redirect; 
use Illuminate\Support\Facades\Session; 
use App\Models\User; 

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return View::make('users.index') ->with('user', $users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        if($validate){
            $user = new User;
            $user->name= Input::get('name');
            $user->email= Input::get('email');
            $user->password = bcrypt(Input::get('password'));
            $user->save(); 
        }
        else{
            return response() ->json([
                'status' => false,
                'message' => 'store failed',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //the compact function is just used for the sake of representing th data via an array
        return View::make('users.show', compact('user'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = $request->validate(array(
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ));

        if($validate){
            $user = User::findOrFail($id);
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->password = Input::get('password');
            $user->save();
        } else{
            return response() ->json([
                'status' => false,
                'message' => 'Update Failed',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);//to handle not the case where the user is not found
        $user->delete();

    }
}
