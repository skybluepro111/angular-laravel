@extends('layouts.main')

@section('content')
<div class="page">

	{{-- @include('partials.slider') --}}
	
	<section>
		{{-- <h1 class="section-heading">Top stories:</h1> --}}

		@include('partials.top-stories')     
	</section>

	<section class="from-the-web">
		<article class="item item--post">
			<div class="content promoted">
				{!! config('custom.content-advertising-1') !!}
			</div>
		</article>
	</section>
</div>

<aside class="sidebar">

	@include('partials.sidebar-articles')

	@if(!empty(config('custom.facebook-url')))
		<div class="sticky sticky--facebook">
			<div class="fb-page" data-href="{{ config('custom.facebook-url') }}" data-height="500" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="{{ config('custom.facebook-url') }}" class="fb-xfbml-parse-ignore"><a href="{{ config('custom.facebook-url') }}">{{ config('custom.app-name') }}</a></blockquote></div>
		</div>
	@endif
</aside>
@endsection  