@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Post</h2>
    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data"> <!-- Add enctype here -->
    @csrf
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file" name="post_image" id="post_image" accept="image/*">
    </div>
    <div class="form-group">
        <label for="content">Content</label>
        <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
    </div>
    
    <!-- Category Dropdown -->
    <div class="form-group">
        <label for="category_id">Category</label>
        <select class="form-control" id="category_id" name="category_id" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Tags Checkboxes -->
    <div class="form-group">
        <label>Tags</label><br>
        @foreach($tags as $tag)
            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"> {{ $tag->name }}<br>
        @endforeach
    </div>

    <button type="submit" class="btn btn-primary">Create Post</button>
</form>
</div>
@endsection
