<header class="header header--main">
    <div class="wrapper wrapper--header">
        <section>
            <a href="{{url('/')}}" class="logo">
                <img src="{!! asset(config('custom.site-logo-path')) !!}" alt="">
            </a>
            <nav class="nav nav--main resize">
                <a href="{{ url('/category/funny') }}" class="funny @if ( $current_category == 'funny') active @endif">Funny</a>
                <a href="{{ url('/category/animals') }}" class="animals @if ( $current_category == 'animals') active @endif">Animals</a>
                <a href="{{ url('/category/news') }}" class="news @if ( $current_category == 'news') active @endif">News</a>
                <a href="{{ url('/category/food') }}" class="food @if ( $current_category == 'food') active @endif">Food</a>
                <a href="{{ url('/category/creepy') }}" class="creepy @if ( $current_category == 'creepy') active @endif">Creepy</a>
                <a href="{{ url('/category/interesting') }}" class="interesting @if ( $current_category == 'interesting') active @endif">Interesting</a>

                <span class="more">More <span class="arrow"><svg><use xlink:href="#svg-arrow"></use></svg></span></span>
            </nav>
            <div class="modal">
                <nav class="nav nav--more cats">
                    <a href="{{ url('/') }}" class="home">Home</a>
                    <a href="{{ url('/trending') }}" class="trending @if ( $current_category == 'trending') active @endif">Trending</a>
                    <a href="{{ url('/category/feels') }}" class="feels @if ( $current_category == 'feels') active @endif">Feels</a>
                    <a href="{{ url('/category/gaming') }}" class="gaming @if ( $current_category == 'gaming') active @endif">Gaming</a>
                </nav>
                <nav class="nav nav--more landing">
                    <a href="{{ url('/terms') }}" @if ( $current_page == 'terms') class="active" @endif>Terms &amp; Conditions</a>
                    <a href="{{ url('/privacy') }}" @if ( $current_page == 'privacy') class="active" @endif>Privacy Policy</a>
                    <a href="{{ url('/copyright') }}" @if ( $current_page == 'copyright') class="active" @endif>DMCA Removal</a>
                    <a href="{{ url('/contact') }}" @if ( $current_page == 'contact') class="active" @endif>Contact Us</a>
                </nav>
                <div class="nav-footer">
                    {{--<a href="{{ url('/submit') }}" class="btn post">Submit post</a>--}}
                    <div class="social">
                        <a href="{{ config('custom.facebook-url', '#') }}" class="facebook" target="_blank"><svg><use xlink:href="#svg-facebook"></use></svg></a>
                        <a href="{{ config('custom.twitter-url', '#') }}" class="twitter" target="_blank"><svg><use xlink:href="#svg-twitter"></use></svg></a>
                        <a href="{{ config('custom.instagram-url', '#') }}" class="instagram" target="_blank"><svg><use xlink:href="#svg-instagram"></use></svg></a>
                        {{--<a href="" class="youtube"><svg><use xlink:href="#svg-youtube"></use></svg></a>--}}
                    </div>
                </div>
            </div>
        </section>

        <aside>
            <div class="share">
                <div class="fb-like" data-href="http://facebook.com/Postize" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
                <a class="twitter-follow-button" href="https://twitter.com/Postize" data-show-count="false"></a>
            </div>

            <span class="magnifier show-search"><svg><use xlink:href="#svg-search"></use></svg></span>

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