<div class="articles">
	@foreach($posts as $post)
	<article class="item {{ strtolower($post->category->name) }}">
		<a href="{{url($post->slug)}}" class="image">
			<figure>
				<img src="{{ $post->image }}" alt="">
			</figure>
			<h2>{{ $post->title }}</h2>
		</a>
        <a href="{{ url('category/' . strtolower($post->category->name)) }}" class="category">{{ $post->category->name }}</a>
	</article>
	@endforeach
</div>