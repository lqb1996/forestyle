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
        $email = request('openId');
        $nickName = request('nickName');
        $openId = request('openId');
        $avatarUrl = request('avatarUrl');
        $gender = request('gender');
        $language = request('language');
        $city = request('city');
        $province = request('province');
        $country = request('country');
        $password = 'cfph111';

        if (true == \Auth::attempt(array('email' => $email,'password' => $password))) {
            $flag = true;
            return compact('email', 'flag');
        }
        else {
//            $user = request(['password', 'nickName', 'openId', 'avatarUrl', 'gender', 'language', 'email', 'city', 'province', 'country']);
            \App\User::firstOrCreate(compact('password', 'nickName', 'openId', 'avatarUrl', 'gender', 'language', 'email', 'city', 'province', 'country'));
            $flag = \Auth::attempt(array('email' => $email,'password' => $password));
            return compact('email', 'flag');
        }

    }

    public function logout()
    {
        \Auth::logout();
        return redirect('/login');
    }
}
