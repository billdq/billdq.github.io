<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all();
        return view('users', ['users' => $users]);
    }

    public function createUser()
    {
        return view('user');
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->fill($request->all());
        $user->{'password'} = bcrypt($request->input("password"));
        $user->{'is_admin'} = $request->input("is_admin") == "Y";
        $user->save();

        return redirect()->route('users', ['id' => $user->{"id"}]);
    }

    public function show($id)
    {
        $user = User::where('id', $id)->first();
        return view('user', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->first();

        if ($user) {
            $user->fill($request->all());
            if (!empty($request->input("password"))) {
                $user->{'password'} = bcrypt($request->input("password"));
            }
            $user->{'is_admin'} = $request->input("is_admin") == "Y";
            $user->save();
        }

        return redirect()->route('users', ['id' => $id]);
    }

    public function destroy($id)
    {
        $user = User::where('id', $id)->first();

        if ($user) {
            $user->delete();
        }

        return "success";
    }
}
