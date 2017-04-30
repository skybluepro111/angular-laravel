@foreach($posts as $post)
    <article class="item item--small {{ strtolower($post->category->name) }}">
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
            	<div>{{ $post->description }}/div>
            </div>
        </div>
        <a href="{{ url('category/' . strtolower($post->category->name)) }}" class="category">{{ $post->category->name }}</a>
    </article>
@endforeach
