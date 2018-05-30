@extends("admin.layout.main")

@section("content")
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-10 col-xs-6">
                <div class="box">

                    <!-- /.box-header -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">修改专题</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form role="form" action="/admin/topics/{{$topic->id}}" method="POST" enctype="multipart/form-data">
                            {{method_field("PATCH")}}
                            {{csrf_field()}}
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">专题名</label>
                                    <input type="text" class="form-control" name="name" value="{{$topic->name}}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">上级专题</label>
                                    <select name="parent_id">
                                        <option value="0">------------</option>
                                        @foreach($topics as $t)
                                        <option value="{{$t->id}}"
                                        @if($t->id == $topic->parent_id) selected @endif
                                        >{{$t->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">封面图片</label>
                                    <div class="form-control">
                                        <input class=" file-loading preview_input" type="file" value="用户名" style="width:150px" name="imgUrl">
                                        <img style="max-height: 300px;" class="preview_img" src="{{$topic->imgUrl}}" alt="" class="img-rounded" style="border-radius:500px;">
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">提交</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection