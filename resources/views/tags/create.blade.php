@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Tag</h2>
    <form method="POST" action="{{ route('tags.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Tag Name</label>
            <input type="text" name="name" class="form-control" id="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Tag</button>
    </form>
</div>
@endsection
