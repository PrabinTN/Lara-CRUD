@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="w-100" style="max-width: 600px;">
        <h2 class="text-center mb-4">Create Post</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group mb-3">
                <label for="title">Title</label>
                <input type="text" class="form-control w-75 @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="post_image">Featured Image</label>
                <input type="file" class="form-control w-75 @error('post_image') is-invalid @enderror" name="post_image" id="post_image" accept="image/*">
                @error('post_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="content">Content</label>
                <textarea class="form-control w-75 @error('content') is-invalid @enderror" id="content" name="content" rows="5">{{ old('content') }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="category_id">Category</label>
                <select class="form-control w-75 @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label>Tags</label><br>
                @foreach($tags as $tag)
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="tags[]" value="{{ $tag->id }}" 
                            {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $tag->name }}</label>
                    </div>
                @endforeach
                @error('tags')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Create Post</button>
        </form>
    </div>
</div>
@endsection
