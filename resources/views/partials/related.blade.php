@foreach($relatedPosts as $post)
    <article class="item item--small {{ strtolower($post->category->name)  }}">
        <a href="{{url($post->slug)}}" class="image">
            <figure>
                <img src="{{ $post->image }}" alt="">
            </figure>
        </a>

        <div class="info">
            <a href="{{url($post->slug)}}">
                <h2>{{ $post->title }}</h2>
            </a>

            <div class="meta">
                <div>by <span class="author">{{$post->author->name}}</span></div>
            </div>
        </div>
    </article>
@endforeach