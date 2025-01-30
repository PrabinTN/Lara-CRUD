@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">My Posts</h2>
    <a href="{{ route('posts.create') }}" class="btn btn-success mb-3">Create New Post</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @foreach($posts as $post)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{{ Str::limit($post->content, 150) }}</p>
                        <p class="text-muted">Category: 
                            <strong>{{ $post->category ? $post->category->name : 'No category assigned' }}</strong>
                        </p>
                        <p class="text-muted">Tags: 
                            @foreach ($post->tags as $index => $tag)
                                <span>{{ $tag->name }}</span>
                                @if (!$loop->last) 
                                    ,
                                @endif
                            @endforeach
                        </p>
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-primary btn-sm">View</a>
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $posts->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
