@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dashboard</h2>
    <p>Welcome, {{ auth()->user()->name }}</p>

    <!-- Create Post Button -->
    <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Create Post</a>

    <!-- Logout Form -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
</div>
@endsection
