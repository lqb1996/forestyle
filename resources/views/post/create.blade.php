@extends("layout.main")

@section("content")

    <div class="col-sm-8 blog-main">
        <form action="/posts" method="POST" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-group">
                <label>标题</label>
                <input name="title" type="text" class="form-control" placeholder="这里是标题">
            </div>
            <div class="form-group">
                <label>简介</label>
                <input name="description" type="text" class="form-control" placeholder="请输入简介">
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">封面图片</label>
                <div class="col-sm-2">
                    <input class=" file-loading preview_input" type="file" value="用户名" style="width:72px" name="imgUrl">
                    <img  class="preview_img" src="" alt="" class="img-rounded" style="border-radius:500px;">
                </div>
            </div></br>
            <div class="form-group">
                <label>内容</label>
                <textarea id="content"  style="height:400px;max-height:500px;" name="content" class="form-control" placeholder="这里是内容"></textarea>
            </div>
            @include("layout.error")
            <button type="submit" class="btn btn-default">提交</button>
        </form>
        <br>

    </div><!-- /.blog-main -->


@endsection