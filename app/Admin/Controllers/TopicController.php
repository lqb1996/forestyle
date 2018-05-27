<?php

namespace App\Admin\Controllers;

use App\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topics = \App\Topic::with('parent')->get();
        return view('admin/topic/index', compact('topics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $topics = \App\Topic::all();
        return view('admin/topic/create', compact('topics'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:1'
        ]);
        $imgUrl = "";
        if ($request->file('imgUrl')) {
            $path = $request->file('imgUrl')->storePublicly(md5(\Auth::id() . time()));
            $imgUrl = env('APP_URL')."/storage/". $path;
        }
        \App\Topic::create(array_merge(request(['name', 'parent_id']), ['imgUrl' => $imgUrl]));
        return redirect('/admin/topics');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic)
    {
        return view('admin/topic/show', compact('topic'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function edit(Topic $topic)
    {
        $topics = \App\Topic::all();
        return view('admin/topic/edit', compact('topic', 'topics'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Topic $topic)
    {
        //
        $topic->update(request(['name', 'parent_id']));
        if ($request->file('imgUrl')) {
            $path = $request->file('imgUrl')->storePublicly(md5(\Auth::id() . time()));
            $imgUrl = env('APP_URL')."/storage/". $path;
            $topic->update(['imgUrl' => $imgUrl]);
        }
        return redirect('/admin/topics');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topic $topic)
    {
        $topic->delete();
        return [
            'error' => 0,
            'msg' => '',
        ];
    }
}
