@extends('layouts.main')

@section('content')

        <section>
            <h1 class="section-heading section-heading--search">Search Results</h1>

            @if(!$posts->isEmpty())
                <div class="articles articles--search">
                @foreach($posts as $post)
                    <article class="item item--big {{ strtolower($post->category->name) }}">
                        <a href="{{url($post->slug)}}" class="image">
                            <figure>
                                <img src="{{$post->image}}" alt="">
                            </figure>
                        </a>
                        <div class="info">
                            <a href="{{url($post->slug)}}">
                                <h1>{{$post->title}}</h1>
                            </a>
                            <p>{{$post->description}}</p>
                            <div class="meta-holder">
                                <div class="meta">
                                    <figure class="avatar">
                                        <img src="{{$post->author->image}}" alt="">
                                    </figure>
                                    <div>by <a href="{{url($post->slug)}}" class="author">{{$post->author->name}}</a> on
                                        <span class="date">{{ DateTime::createFromFormat('Y-m-d H:i:s', $post->created_at)->format('jS F, Y') }}</span></div>
                                    </div>
                                    {{-- <a href="{{url($post->slug)}}" class="btn">Read more</a> --}}
                                    <div class="row share-buttons small">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{url($post->slug)}}" target="_blank"
                                         class="row facebook">
                                         <div>
                                            <svg>
                                                <use xlink:href="#svg-facebook"></use>
                                            </svg>
                                        </div>
                                        <span>Share</span>
                                        </a>
                                        <a href="https://twitter.com/intent/tweet?text=Take%20a%20look%20at%20this&amp;url={{url($post->slug)}}"
                                         class="row twitter">
                                         <div>
                                            <svg>
                                                <use xlink:href="#svg-twitter"></use>
                                            </svg>
                                        </div>
                                        <span>Tweet</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <a href="{{ url('category/' . strtolower($post->category->name)) }}" class="category">{{ $post->category->name }}</a>
                    </article>
                @endforeach
                </div>
            @else
                <h2 class="section-heading section-heading--search">Oops, couldn't find what you were looking for. Check out one of our other stories:</h2>
                <div class="articles articles--search">
                @include('partials.top-stories')
                </div>
            @endif

        </section>

@endsection  