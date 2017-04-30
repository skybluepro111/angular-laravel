<section class="slider">
    @foreach($posts as $post)
        <article class="item {{ strtolower($post->category->name) }}">
            <a href="{{url($post->slug)}}" class="image">
                <figure>
                    <img src="{{ $post->image }}" alt="">
                </figure>
            </a>
            <aside>
                <a href="{{url($post->slug)}}">
                    <h1 class="main">{{$post->title}}</h1>
                </a>

                <div class="meta">by <a href="{{url($post->slug)}}" class="author">{{$post->author->name}}</a> on
                    <span class="date">{{ DateTime::createFromFormat('Y-m-d H:i:s', $post->created_at)->format('jS F, Y') }}</span>
                </div>
                <a href="{{url($post->slug)}}" class="btn">Read more</a>
            </aside>
            <a href="{{ url('category/' . strtolower($post->category->name)) }}" class="category">{{ $post->category->name }}</a>
        </article>
    @endforeach
</section> 