<?php

namespace forestyle\Http\Controllers;

use Illuminate\Http\Request;
use forestyle\Topic;

class TopicController extends Controller
{
    /*
     * topic详情页
     */
    public function show(Topic $topic)
    {
        $posts = $topic->posts()->orderBy('created_at', 'desc')->with(['user'])->take(10)->get();
        $me = \Auth::user();

        $myposts = \forestyle\Post::authorBy(\Auth::id())->topicNotBy($topic->id)->get();

        return view('topic/show', compact('topic', 'posts', 'myposts'));
    }

    //topic详情接口
    public function index(Topic $topic)
    {
        $posts = $topic->posts()->orderBy('created_at', 'desc')->with(['user', 'topics'])->take(10)->get();
        return compact('topic', 'posts');
    }

    /*
     * 投稿
     */
    public function submit(Topic $topic)
    {
        $this->validate(request(),[
            'post_ids' => 'array'
        ]);

        // 确认这些post都是属于当前用户的
        $posts = \forestyle\Post::find(request(['post_ids']));
        foreach ($posts as $post) {
            if ($post->user_id != \Auth::id()) {
                return back()->withErrors(array('message' => '没有权限'));
            }
        }


        $post_ids = request('post_ids');
        $topic_id = $topic->id;
        foreach ($post_ids as $post_id){
            \forestyle\PostTopic::firstOrCreate(compact('topic_id', 'post_id'));
        }
        return back();
    }
}
