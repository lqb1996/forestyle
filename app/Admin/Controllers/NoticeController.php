<?php

namespace forestyle\Admin\Controllers;

use forestyle\Topic;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notices = \forestyle\Notice::all();
        return view('admin/notice/index', compact('notices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/notice/create');
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
            'title' => 'required|min:3',
            'content' => 'required|min:3'
        ]);

        $notice = \forestyle\Notice::create(request(['title', 'content']));

        dispatch(new \forestyle\Jobs\SendMessage($notice));

        return redirect('/admin/notices');
    }

    /**
     * Display the specified resource.
     *
     * @param  \forestyle\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \forestyle\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \forestyle\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Topic $topic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \forestyle\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
    }
}
