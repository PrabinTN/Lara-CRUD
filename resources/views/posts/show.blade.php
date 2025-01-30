@extends('layouts.app')

@section('content')
<div class="container">
<div class="post">
    <h2>{{ $post->title }}</h2>
    <p>{{ $post->content }}</p>

    <!-- Display Category -->
    <p><strong>Category:</strong> {{ $post->category->name }}</p>

    <!-- Display Tags -->
    <p><strong>Tags:</strong>
        @foreach($post->tags as $tag)
            <span class="badge badge-info">{{ $tag->name }}</span>
        @endforeach
    </p>
</div>

</div>
@endsection
