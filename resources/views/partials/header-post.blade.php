<header class="header header--post">
    <div class="wrapper wrapper--header">
        <section>
            <a href="{{url('/')}}" class="logo">
                <img src="{{ url(config('custom.site-icon-path')) }}" alt="">
            </a>
            <h2 class="resize-post-header">{{ $post->title }}</h2>
        </section>

        <aside>
            <div class="share">
                <div class="row share-buttons extra-small">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{url($post->slug)}}" target="_blank"
                       class="row facebook">
                        <div>
                            <svg>
                                <use xlink:href="#svg-facebook"></use>
                            </svg>
                        </div>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{url($post->slug)}}" class="row twitter">
                        <div>
                            <svg>
                                <use xlink:href="#svg-twitter"></use>
                            </svg>
                        </div>
                    </a>
                </div>
            </div>

            @if(!$post->is_last_page)
                <a href="{{ $nextPageUrl }}" class="btn btn--next-post">Next Page</a>
            @else
                <a href="{{ url($nextPost->slug) }}" class="btn btn--next-post">Next Post</a>
            @endif

            {{-- <span class="magnifier show-search"><svg><use xlink:href="#svg-search"></use></svg></span> --}}

            <button class="hamburger hamburger--spring toggle-nav" type="button">
				<span class="hamburger-box">
					<span class="hamburger-inner"></span>
				</span>
            </button>

            <div class="search">
                <form action="{{url('search')}}">
                    <input type="text" name="s" placeholder="Search {{ config('custom.app-name') }}">
                </form>
            </div>
        </aside>
    </div>
</header>