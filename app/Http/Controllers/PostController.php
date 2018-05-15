<?php

namespace forestyle\Http\Controllers;

use forestyle\Comment;
use forestyle\Relationship;
use Illuminate\Http\Request;
use forestyle\Post;
use forestyle\Topic;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /*
     * 首页接口
     */
    public function index(Request $request)
    {
        $user = \Auth::user();
        $banners = Topic::with('posts')->find(1);
        $recommends = Topic::with('children')->find(2);
        $posts = Post::aviable()->orderBy('created_at', 'desc')->withCount(["targets", "comments"])->with(['topics'])->paginate(6);
        if($request['type'] == 'ajax'){
            return compact('posts','banners','recommends');
        }
        return view('post/index', compact('posts','banners','recommends'));
//        return $posts;
    }
    /*
     * 森究堂接口
     */
    public function senJiuTang(Request $request)
    {
        $user = \Auth::user();
//        $banners = Topic::find(1)->with('posts')->get();
        $topics = Topic::with('posts.topics')->where('parent_id', 8)->orderBy('created_at', 'desc')->get();
        return compact('topics');
    }
    /*
     * 活动接口
     */
    public function activity(Request $request)
    {
        $user = \Auth::user();
        $topics = Topic::with('posts')->orderBy('created_at', 'desc')->find(7);
        return compact('topics');
    }

    public function imageUpload(Request $request)
    {
        $path = $request->file('wangEditorH5File')->storePublicly(md5(\Auth::id() . time()));
        return asset(env('APP_URL').'/storage/'. $path);
    }

    public function create()
    {
        return view('post/create');
    }

    public function store(Request $request)

    {
        $this->validate($request, [
            'title' => 'required|max:255|min:4',
            'description' => 'required|max:100',
            'content' => 'required|min:100',
        ]);
        $params = array_merge(request(['title', 'description', 'content']), ['user_id' => \Auth::id()]);

        if ($request->file('imgUrl')) {
            $path = $request->file('imgUrl')->storePublicly(md5(\Auth::id() . time()));
            $imgUrl = env('APP_URL')."/storage/". $path;
            $params = array_merge($params, ['imgUrl' => $imgUrl]);
        }
        Post::create($params);
        return redirect('/posts');
    }
    public function edit(Post $post)
    {
        return view('post/edit', compact('post'));
    }

    public function show(Request $request, \forestyle\Post $post)
    {
        $post = Post::with('comments.user', 'targets', 'user', 'topics')->find($post->id);
        $hasZan = \Auth::user()->hasZan($post->id, 'forestyle\Post');
        if($request['type'] == 'ajax'){
            return compact('post', 'hasZan');
        }
        return view('post/show', compact('post', 'isZan'));
    }

    public function update(Request $request, Post $post)
    {
        $this->validate($request, [
            'title' => 'required|max:255|min:4',
            'description' => 'required|max:100',
            'content' => 'required|min:100',
        ]);

        $this->authorize('update', $post);
        $param = request(['title', 'description', 'content']);
        if ($request->file('imgUrl')) {
            $path = $request->file('imgUrl')->storePublicly(md5(\Auth::id() . time()));
            $imgUrl = env('APP_URL')."/storage/". $path;
            $param = array_merge($param, ['imgUrl' => $imgUrl]);
        }
        $post->update($param);
        return redirect("/posts/{$post->id}");
    }

    /*
     * 文章评论保存
     */
    public function comment(Request $request)
    {
        $this->validate(request(),[
            'post_id' => 'required|exists:posts,id',
//            'content' => 'required|min:10',
        ]);

        $user_id = \Auth::id();
        $post = new Post();
        $comment = new Comment();
        $comment->user_id = $user_id;
        $comment->content = request('content');
        $post->id = request('post_id');
//        $params = array_merge(
//            compact('commentable_id'),
//            request(['content']),
//            compact('user_id'),
//            compact('parent_id')
//        );
//        \forestyle\Comment::create($params);
        $post->commentable()->save($comment);
        return compact('post');
    }

    /*
     * 点赞
     */
    public function zan(Post $post)
    {
//        $zan = new \forestyle\Zan;
//        $zan->user_id = \Auth::id();
//        $post->zans()->save($zan);
//        $post->target()->save($zan);


        $relationship = new Relationship();
        //commentable_type取值例如：forestyle\Post，forestyle\Page等等
//        $target = app('forestyle\Post')->where('id', $post->id)->firstOrFail();
        $relationship->user_id = \Auth::id();
        $post->targets()->save($relationship);
        return compact('post');
    }

    /*
     * 取消点赞
     */
    public function unzan(Post $post)
    {
        $post->target(\Auth::id())->delete();
        return compact('post');
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
        $posts = Post::search(request('query'))->paginate(10);
        return view('post/search', compact('posts', 'query'));
    }
}
