@extends('layouts.main')

@section('content')
<div class="landing">

    <section class="content center">
        <h1 class="section-heading big">404</h1>

        <h2>Page Not Found</h2>

    </section>

    <section class="content center">
        <h2>Maybe you can find something even better than where you were trying to go. Check out our top stories below.</h2>

        <div class="articles articles--search">
            @include('partials.top-stories')
        </div>    
    </section>

</div>

@endsection  