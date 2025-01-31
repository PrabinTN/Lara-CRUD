@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">My Posts</h2>
    <a href="{{ route('posts.create') }}" class="btn btn-success mb-3">Create New Post</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Tags</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $index => $post)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($post->post_image)
                                <img src="{{ asset('storage/' . $post->post_image) }}" alt="Post Image" class="img-thumbnail" width="100">
                            @else
                                <span>No Image</span>
                            @endif
                        </td>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->category ? $post->category->name : 'No category assigned' }}</td>
                        <td>
                            @foreach ($post->tags as $tag)
                                <span class="badge bg-primary">{{ $tag->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('posts.show', $post) }}" class="btn btn-primary btn-sm">View</a>
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $posts->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
