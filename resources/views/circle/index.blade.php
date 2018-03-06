@extends("layout.main")

@section("content")
    <div class="col-sm-8 blog-main">
        @include("circle.carousel")
        <div style="height: 20px;">
        </div>
        <div>
            @foreach($circles as $circle)
            <div class="blog-post">
                <h2 class="blog-post-title"><a href="/user/{{$circle->user_id}}" >{{$circle->user->nickName}}</a></h2>

                {!! str_limit($circle->content, 100, '...') !!}<br/>
                @foreach($circle->circleImgs as $circleImg)
                <img src="{{$circleImg->imgUrl}}"/>
                @endforeach
                    {{--<img src="http://pic4.nipic.com/20091217/3885730_124701000519_2.jpg"/>--}}
                {{--<p class="blog-post-meta">{{$post->created_at->toFormattedDateString()}} by <a href="/user/{{$post->user_id}}">{{$post->user->name}}</a></p>--}}

{{--                <p class="blog-post-meta">赞 {{$post->zans_count}}  | 评论 {{$post->comments_count}}</p>--}}
            </div>
            @endforeach

{{--            {{$posts->links()}}--}}
        </div><!-- /.blog-main -->
    </div>
@endsection