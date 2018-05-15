<?php

namespace forestyle\Http\Controllers;

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

    public function request_get($url = '',$param = '')
    {
        if (empty($url)) {
            return false;
        }

        $getUrl = $url;
        $getCurl = curl_init();//初始化curl
        curl_setopt($getCurl, CURLOPT_URL, $getUrl);//抓取指定网页
        curl_setopt($getCurl, CURLOPT_HEADER, false);
        curl_setopt($getCurl, CURLOPT_RETURNTRANSFER, true);
        if (!empty($param)) {
            curl_setopt($getCurl, CURLOPT_POST, 1);//如果存在param则采用post提交方式
            curl_setopt($getCurl, CURLOPT_POSTFIELDS, $param);
        }
        $data = curl_exec($getCurl);//运行curl
        curl_close($getCurl);

        return $data;
    }

    public function loginWeChat(Request $request)
    {
        if(!$request['code']){
            return null;
        }
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='
                .env('WECHAT_APPID')
                .'&secret='.env('WECHAT_APP_SECRET')
                .'&js_code='.$request['code'].'&grant_type=authorization_code';
        $res = json_decode($this->request_get($url), true);
        return compact('res');
//        $this->validate($request, [
//            'openId' => ''
//        ]);
//        $email = request('openId');
//        $nickName = request('nickName');
//        $openId = request('openId');
//        $avatarUrl = request('avatarUrl');
//        $gender = request('gender');
//        $language = request('language');
//        $city = request('city');
//        $province = request('province');
//        $country = request('country');
//        $psd = '1234567890';
//        $password = bcrypt($psd);
//
//        if (true == \Auth::attempt(array('email' => $email,'password' => $psd))) {
//            $flag = true;
//            $user = \Auth::user();
//            return compact('user', 'flag');
//        }
//        else {
//            $user = request(['password', 'nickName', 'openId', 'avatarUrl', 'gender', 'language', 'email', 'city', 'province', 'country']);
//            \forestyle\User::firstOrCreate(compact('password', 'nickName', 'openId', 'avatarUrl', 'gender', 'language', 'email', 'city', 'province', 'country'));
//            $flag = \Auth::attempt(array('email' => $email,'password' => $psd));
//            $user = \Auth::user();
//            return compact('user', 'flag');
//        }

    }

    public function logout()
    {
        \Auth::logout();
        return redirect('/login');
    }
}
