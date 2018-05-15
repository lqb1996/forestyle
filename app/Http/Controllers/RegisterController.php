<?php

namespace forestyle\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register/index');
    }

    public function register()
    {
        $this->validate(request(),[
            'nickName' => 'required|min:3|unique:users,nickName',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:5|confirmed',
        ]);

        $password = bcrypt(request('password'));
        $nickName = request('nickName');
        $email = request('email');
        $user = \forestyle\User::create(compact('nickName', 'email', 'password'));
        return redirect('/login');
    }
}
