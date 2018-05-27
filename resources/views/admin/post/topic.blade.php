@extends("admin.layout.main")

@section("content")
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-10 col-xs-6">
                <div class="box">

                    <div class="box-header with-border">
                        <h3 class="box-title">{{$post->title}}</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="/admin/posts/{{$post->id}}/store" method="POST">
                            {{csrf_field()}}
                            <div class="form-group">
                                @foreach($topics as $topic)
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="topics[]"
                                                   @if ($myTopics->contains($topic))
                                                   checked
                                                   @endif
                                                   value="{{$topic->id}}">
                                            {{$topic->name}}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
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