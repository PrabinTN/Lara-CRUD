<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::resource('posts', PostController::class)->middleware('auth');

Route::get('/posts/category/{category}', [PostController::class, 'filterByCategory'])->name('posts.filter.category');

Route::get('/posts/tag/{tag}', [PostController::class, 'filterByTag'])->name('posts.filter.tag');

Route::resource('categories', CategoryController::class);

Route::resource('tags', TagController::class);
