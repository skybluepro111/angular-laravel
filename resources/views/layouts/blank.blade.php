<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta property="og:title" content="{{ $post->title or config('custom.app-name') }}">
    <meta property="og:description" content="{{$post->description or 'Sharable stories galore.' }}">
    <meta property="og:image" content="{{ $post->image or ''}}">
    <meta property="og:url" content="{{ !empty($post) ? url($post->slug) : Request::url() }}">
    <meta property="og:site_name" content="{{ config('custom.app-name') }}">
    <meta property="og:type" content="article">
    <meta property="article:author" content="https://www.facebook.com/Postize"/>
    <meta property="article:publisher" content="https://www.facebook.com/Postize"/>
    <meta property="fb:app_id" content="1979088928983040"/>

    <meta name="_token" content="{!! csrf_token() !!}"/>

    <title>{{ config('custom.app-name') }}</title>
    @yield('css')
    @yield('js-top')
</head>

<body>
@yield('content')

<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
@yield('js-bottom')
</body>

</html>