@extends("layout.main")

@section("content")

    <div class="col-sm-8 blog-main">
        <form action="/posts/{{$post->id}}" method="POST" enctype="multipart/form-data">
            {{method_field("PUT")}}
            {{csrf_field()}}
            <div class="form-group">
                <label>标题</label>
                <input name="title" type="text" class="form-control" placeholder="这里是标题" value="{{$post->title}}">
            </div>
            <div class="form-group">
                <label>简介</label>
                <input name="description" type="text" class="form-control" placeholder="请输入简介" value="{{$post->description}}">
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">封面图片</label>
                <div class="col-sm-2">
                    <input class=" file-loading preview_input" type="file" value="用户名" style="width:72px" name="imgUrl">
                    <img  class="preview_img" src="{{$post->imgUrl}}" alt="" class="img-rounded" style="border-radius:500px;">
                </div>
            </div>
            <div class="form-group">
                <label>内容</label>
                <textarea id="content" name="content" class="form-control" style="height:400px;max-height:500px;"  placeholder="这里是内容">{{$post->content}}</textarea>
            </div>
            <button type="submit" class="btn btn-default">提交</button>
        </form>
        <br>
        @include('layout.error')
    </div><!-- /.blog-main -->


@endsection