<?php

namespace App\Http\Controllers;

use App\Circle;
use App\Topic;
use Illuminate\Http\Request;

class CircleController extends Controller
{
    /*
     * 文章列表
     */
    public function index(Request $request)
    {
        $user = \Auth::user();
        $banners = Topic::with('posts')->find(1);
        $hotTopic = Topic::where('parent_id', 13)->get();
        $circles = Circle::aviable()->orderBy('created_at', 'desc')->with('user', 'circleImgs')->withCount(["targets", "comments"])->paginate(20);
        return compact('banners', 'hotTopic', 'circles');
    }

    public function imageUpload(Request $request)
    {
        $path = $request->file('wangEditorH5File')->storePublicly(md5(\Auth::id() . time()));
        return asset('115.159.196.225/storage/'. $path);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255|min:4',
            'content' => 'required|min:100',
        ]);
        $params = array_merge(request(['content', '']), ['user_id' => \Auth::id()]);
        Circle::create($params);
        return redirect('/circles');
    }

    public function edit(Circle $circle)
    {
        return view('circle/edit', compact('circle'));
    }

    public function show(Request $request, \App\Circle $circle)
    {
        $circle = Circle::with('user', 'comments', 'circleImgs', 'targets')->find($circle->id);
        return compact('circle');
    }

    public function update(Request $request, Circle $circle)
    {
        $this->validate($request, [
            'title' => 'required|max:255|min:4',
            'content' => 'required|min:100',
        ]);

        $this->authorize('update', $circle);

        $circle->update(request(['title', 'content']));
        return redirect("/circles/{$circle->id}");
    }

    /*
     * 文章评论保存
     */
    public function comment()
    {
        $this->validate(request(),[
            'circle_id' => 'required|exists:circles,id',
            'content' => 'required|min:10',
        ]);

        $user_id = \Auth::id();

        $params = array_merge(
            request(['circle_id', 'content']),
            compact('user_id')
        );
        \App\Comment::create($params);
        return back();
    }

    /*
     * 点赞
     */
    public function zan(Circle $circle)
    {
        $relationship = new Relationship();
        //commentable_type取值例如：App\Post，App\Page等等
//        $target = app('App\Post')->where('id', $post->id)->firstOrFail();
        $relationship->user_id = \Auth::id();

        $circle->targets()->save($relationship);
        return back();
    }

    /*
     * 取消点赞
     */
    public function unzan(Circle $circle)
    {
        $circle->target(\Auth::id())->delete();
        return back();
    }

    /*
     * 搜索页面
     */
    public function search()
    {
        $this->validate(request(),[
            'query' => 'required'
        ]);

        $query = request('query');
        $circles = Circle::search(request('query'))->paginate(10);
        return view('circle/search', compact('circles', 'query'));
    }
}