<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    /*
     * 个人介绍页面
     */
    public function show(User $user)
    {

        // 这个人的文章
        $posts = $user->posts()->orderBy('created_at', 'desc')->take(10)->get();
        // 这个人的关注／粉丝／文章
        $user = \App\User::withCount(['stars', 'fans', 'posts'])->find($user->id);
        $fans = $user->fans($user->id)->get();
        $stars = $user->stars()->get();

        return view("user/show", compact('user', 'posts', 'fans', 'stars'));
//        return response(json_encode(compact('user', 'posts', 'fans', 'stars'), JSON_UNESCAPED_UNICODE));
//        return $stars;
    }

    public function fan(User $user)
    {
        $me = \Auth::user();
//        \App\Fan::firstOrCreate(['fan_id' => $me->id, 'star_id' => $user->id]);
        \App\Relationship::firstOrCreate(['user_id' => $me->id, 'target_id' => $user->id, 'target_type' => 'App\User']);
        return [
            'error' => 0,
            'msg' => ''
        ];
    }

    public function unfan(User $user)
    {
        $me = \Auth::user();
//        \App\Fan::where('fan_id', $me->id)->where('star_id', $user->id)->delete();
        \App\Relationship::where('user_id',$me->id)->where('target_id', $user->id)->where('target_type', 'App\User')->delete();
        return [
            'error' => 0,
            'msg' => ''
        ];
    }

    public function setting()
    {
        $me = \Auth::user();
        return view('user/setting', compact('me'));
    }

    public function settingStore(Request $request, User $user)
    {
        $this->validate(request(),[
            'nickName' => 'min:3',
        ]);

        $nickName = request('nickName');
        if ($nickName != $user->nickName) {
            if(\App\User::where('nickName', $nickName)->count() > 0) {
                return back()->withErrors(array('message' => '用户名称已经被注册'));
            }
            $user->nickName = request('nickName');
        }
        if ($request->file('avatarUrl')) {
//            return back()->withErrors(array('message' => $request->file('avatarUrl')));
            $path = $request->file('avatarUrl')->storePublicly(md5(\Auth::id() . time()));
            $user->avatarUrl = "/storage/". $path;
        }

        $user->save();
        return back();
    }
}
