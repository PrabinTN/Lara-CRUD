<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category; // Add this line for categories
use App\Models\Tag; // Add this line for tags
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // Ensure `middleware()` is inside the constructor
    public function __construct()
    {
        $this->middleware('auth'); //This is correct
    }

    public function index()
    {
        $posts = Post::where('user_id', Auth::id())->paginate(4);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all(); // Get all categories
        $tags = Tag::all(); // Get all tags
        return view('posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id', // Validate category
            'tags' => 'array|exists:tags,id', // Validate tags
        ]);
    
        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id, // Save selected category
        ]);
    
        // Attach selected tags
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

        $categories = Category::all(); // Get all categories
        $tags = Tag::all(); // Get all tags
        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id', // Validate category
            'tags' => 'array|exists:tags,id', // Validate tags
        ]);

        // Update post
        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id, // Update category
        ]);

        // Sync selected tags
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
