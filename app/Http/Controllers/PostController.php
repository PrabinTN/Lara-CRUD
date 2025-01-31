<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Requests\PostRequest; // Import the PostRequest
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $posts = Post::where('user_id', Auth::id())->paginate(4);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.create', compact('categories', 'tags'));
    }

    public function store(PostRequest $request)
    {
        // Handle file upload
        $postImagePath = $request->hasFile('post_image')
            ? $request->file('post_image')->store('images/posts', 'public')
            : null;

        // Create post
        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'post_image' => $postImagePath,
        ]);

        // Attach tags
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(PostRequest $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        // Handle image update
        if ($request->hasFile('post_image')) {
            if ($post->post_image && Storage::exists('public/' . $post->post_image)) {
                Storage::delete('public/' . $post->post_image);
            }
            $postImagePath = $request->file('post_image')->store('images/posts', 'public');
            $post->post_image = $postImagePath;
        }

        // Update post
        $post->update($request->only(['title', 'content', 'category_id']));

        // Sync tags
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete the post image if it exists
        if ($post->post_image && Storage::exists('public/' . $post->post_image)) {
            Storage::delete('public/' . $post->post_image);
        }

        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }

    public function filterByCategory(Category $category)
    {
        $posts = $category->posts()->get();
        return view('posts.index', compact('posts'));
    }

    public function filterByTag(Tag $tag)
    {
        $posts = $tag->posts()->get();
        return view('posts.index', compact('posts'));
    }
}
