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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return View::make('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ];

        $validator = Validator::make(Input::all(), $validate);

        if($validator -> fails()){
            return Redirect::to('users/create')->withErrors($validator)
            ->withInput($request->except('password'));
        } else{
            $user = new User;
            $user->name= Input::get('name');
            $user->name= Input::get('email');
            $user->password = bcrypt(input::get('password'));
            $user->save();

            Session::flash('message', 'User Created Successfully');
            return Redirect::to('users');
            
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $validate = array(
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        );

        $validator = Validator::make(Input::all(), $validate);

        if($validator-> fails()){
            return Redirect::to('users/' . $id . '/edit') -> withErrors($validator)
            ->withInput(Input::expect('password'));
        }
        else{
            $user = User::findOrFail($id);
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->password = Input::get('password');
            $user->save();

            Session::flash('message', 'Successfully Updated');
            return Redirect::to('users');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);//to handle not the case where the user is not found
        $user->delete();

        Session::flash('message', 'Successfully deleted');
        return Redirect::to('users');
    }
}
