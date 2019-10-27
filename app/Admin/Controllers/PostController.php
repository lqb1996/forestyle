<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = \App\Post::with('topics')->orderBy('created_at', 'desc')->paginate(10);
        return view('/admin/post/index', compact('posts'));
    }

    /*
     * 修改文章的状态
     */
    public function status(\App\Post $post)
    {
        $this->validate(request(), [
            "status" => 'required|in:-1,0,1',
        ]);

        $post->status = request('status');
        $post->save();
        return [
            'error' => 0,
            'msg' => ''
        ];
    }

    public function topic(\App\Post $post)
    {
        $topics = \App\Topic::all();
        $myTopics = $post->topics;
        return view('/admin/post/topic', compact('post', 'topics', 'myTopics'));
    }

    public function storeTopic(\App\Post $post)
    {
        $this->validate(request(),[
        'topics' => 'required|array'
        ]);

        $topics = \App\Topic::find(request('topics'));
        $myTopics = $post->topics;

        $addTopics = $topics->diff($myTopics);
        foreach ($addTopics as $topic) {
            $post->grantTopic($topic);
        }

        $deleteTopics = $myTopics->diff($topics);
        foreach ($deleteTopics as $topic) {
            $post->deleteTopic($topic);
        }

        $posts = \App\Post::with('topics')->orderBy('created_at', 'desc')->paginate(10);
        return view('/admin/post/index', compact('posts'));
    }

}
