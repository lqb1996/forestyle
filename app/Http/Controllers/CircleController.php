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
        $path = $request->file('file')->storePublicly(md5(\Auth::id() . time()));
        return asset(env('APP_URL').'/storage/'. $path);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255|min:4',
            'content' => 'required|min:100',
        ]);
        $params = array_merge(request(['content', '']), ['user_id' => \Auth::id()],request(['circle_imgs']));
        Circle::create($params);
        return compact('params');
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
    public function comment(Circle $circle)
    {
        $this->validate(request(),[
            'circle_id' => 'required|exists:circles,id',
            'content' => 'required|min:10',
        ]);

        $user_id = \Auth::id();

        $this->validate(request(),[
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|min:10',
        ]);

        $user_id = \Auth::id();
        $commentable_id = request('circle_id');
        $comment = new Comment();
        $comment->user_id = $user_id;
        $comment->content = request('content');
        $circle->id = request('circle_id');

//        $params = array_merge(
//            compact('commentable_id'),
//            request(['content']),
//            compact('user_id'),
//            compact('parent_id')
//        );
//        \App\Comment::create($params);
        $circle->commentable()->save($comment);
        return compact('circle');
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