<?php

namespace forestyle\Http\Controllers;

use forestyle\Circle;
use Illuminate\Http\Request;
use forestyle\User;

class UserController extends Controller
{
    /*
     * 个人介绍页面
     */
    public function show(Request $request, User $user)
    {
        // 这个人的文章
        $posts = $user->posts()->withCount('targets', 'comments')->orderBy('created_at', 'desc')->take(10)->get();
        // 这个人的关注／粉丝／文章
        $user = \forestyle\User::withCount(['stars', 'fans', 'posts'])->find($user->id);
        $circles = $user->circles()->with( 'circleImgs')->withCount('targets', 'comments')->orderBy('created_at', 'desc')->take(20)->get();
        $fans = $user->fans($user->id)->get();
        $stars = $user->stars()->get();

        if($request['type'] == 'ajax'){
            return compact('user', 'posts', 'circles', 'fans', 'stars');
        }
        return view("user/show", compact('user', 'posts', 'fans', 'stars'));
    }

    public function fan(User $user)
    {
        $me = \Auth::user();
//        \forestyle\Fan::firstOrCreate(['fan_id' => $me->id, 'star_id' => $user->id]);
        \forestyle\Relationship::firstOrCreate(['user_id' => $me->id, 'target_id' => $user->id, 'target_type' => 'forestyle\User']);
        return [
            'error' => 0,
            'msg' => ''
        ];
    }

    public function unfan(User $user)
    {
        $me = \Auth::user();
//        \forestyle\Fan::where('fan_id', $me->id)->where('star_id', $user->id)->delete();
        \forestyle\Relationship::where('user_id',$me->id)->where('target_id', $user->id)->where('target_type', 'forestyle\User')->delete();
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
            if(\forestyle\User::where('nickName', $nickName)->count() > 0) {
                return back()->withErrors(array('message' => '用户名称已经被注册'));
            }
            $user->nickName = request('nickName');
        }
        if ($request->file('avatarUrl')) {
//            return back()->withErrors(array('message' => $request->file('avatarUrl')));
            $path = $request->file('avatarUrl')->storePublicly(md5(\Auth::id() . time()));
            $user->avatarUrl = env('APP_URL')."/storage/". $path;
        }

        $user->save();
        return back();
    }
}
