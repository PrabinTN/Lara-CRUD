@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Post</h2>
    <form method="POST" action="{{ route('posts.update', $post) }}">
        @csrf
        @method('PUT')

        <!-- Title Input -->
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="{{ $post->title }}">
            @error('title') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- Content Input -->
        <div class="mb-3">
            <label>Content</label>
            <textarea name="content" class="form-control">{{ $post->content }}</textarea>
            @error('content') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- Category Selection -->
        <div class="mb-3">
            <label>Category</label>
            <select name="category_id" class="form-control">
                <option value="">Select a category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- Tag Selection -->
        <div class="mb-3">
            <label>Tags</label>
            <div>
                @foreach($tags as $tag)
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                               @if(in_array($tag->id, $post->tags->pluck('id')->toArray())) checked @endif
                               class="form-check-input">
                        <label class="form-check-label">{{ $tag->name }}</label>
                    </div>
                @endforeach
            </div>
            @error('tags') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
