<footer class="site-footer">
    <div class="wrapper text">
        <section>
            <a href="{{url('/')}}" class="logo">
                <img src="{!! asset(config('custom.site-logo-path')) !!}" alt="">
            </a>
            <p>{{config('custom.app-name')}}, {{config('custom.og-description-default')}}</p>
        </section>
        <aside>
            <nav class="nav nav--footer">
                <a href="{{ url('/terms') }}" @if ( $current_page == 'terms') class="active" @endif>Terms &amp; Conditions</a>
                <a href="{{ url('/privacy') }}" @if ( $current_page == 'privacy') class="active" @endif>Privacy Policy</a>
                <a href="{{ url('/copyright') }}" @if ( $current_page == 'copyright') class="active" @endif>DMCA Removal</a>
                <a href="{{ url('/contact') }}" @if ( $current_page == 'contact') class="active" @endif>Contact Us</a>
            </nav>
            <div class="social">
                <a href="{{ config('custom.facebook-url') }}" class="facebook" target="_blank"><svg><use xlink:href="#svg-facebook"></use></svg></a>
                <a href="{{ config('custom.twitter-url') }}" class="twitter" target="_blank"><svg><use xlink:href="#svg-twitter"></use></svg></a>
                <a href="{{ config('custom.instagram-url') }}" class="instagram" target="_blank"><svg><use xlink:href="#svg-instagram"></use></svg></a>
                {{--<a href="" class="youtube"><svg><use xlink:href="#svg-youtube"></use></svg></a>--}}
            </div>
        </aside>
    </div>
    <div class="copyright">
        <div class="wrapper">&copy; <?php echo date('Y') ?> Methodize Media Pty. Ltd. - All Rights Reserved</div>
    </div>
</footer>