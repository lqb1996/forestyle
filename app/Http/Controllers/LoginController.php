<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        if(\Auth::check()) {
            return redirect("/posts");
        }

        return view("login/index");
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6|max:30',
            'is_remember' => '',
        ]);

        $user = request(['email', 'password']);
        $remember = boolval(request('is_remember'));
        if (true == \Auth::attempt($user, $remember)) {
           return redirect('/posts');
        }

        return \Redirect::back()->withErrors("用户名密码错误");
    }

    public function loginWeChat(Request $request)
    {
//        $this->validate($request, [
//            'openId' => ''
//        ]);
        $user = request(['openId']);
        if (true == \Auth::attempt($user)) {
            return compact($user);
        }
        $user = request(['nickName', 'openId', 'avatarUrl','gender','language','email','city','province','country']);
        \App\User::create(compact($user));
        return \Auth::attempt($user);

    }

    public function logout()
    {
        \Auth::logout();
        return redirect('/login');
    }
}
