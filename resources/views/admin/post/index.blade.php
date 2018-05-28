@extends("admin.layout.main")

@section("content")
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-10 col-xs-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">文章列表</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tbody><tr>
                                <th style="width: 10px">#</th>
                                <th>文章标题</th>
                                <th>文章简介</th>
                                <th>所属专题</th>
                                <th>操作</th>
                            </tr>
                            @foreach($posts as $post)
                            <tr>
                                <td>{{$post->id}}.</td>
                                <td>{{$post->title}}</td>
                                <td>{{$post->description}}</td>
                                <td>@foreach($post->topics as $topic)
                                        {{$topic->name}}
                                        @endforeach
                                </td>
                                <td>
                                    <button type="button" class="btn btn-block btn-default post-redirect" post-id="{{$post->id}}" >话题分类</button>
                                    @if ($post->status != 1)
                                    <button type="button" class="btn btn-block btn-default post-audit" post-id="{{$post->id}}" post-action-status="1">审核通过</button>
                                    @endif
                                    @if ($post->status != -1)
                                        <button type="button" class="btn btn-block btn-default post-audit" post-id="{{$post->id}}" post-action-status="-1">审核不通过</button>
                                        @endif
                                </td>
                            </tr>
                            @endforeach
                            </tbody></table>
                    </div>
                    {{$posts->links()}}
                </div>
            </div>
        </div>
    </section>
@endsection