<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category; // Add this line for categories
use App\Models\Tag; // Add this line for tags
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure user is authenticated
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
        // Validate incoming data
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array|exists:tags,id',
            'post_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image
        ]);

        // Handle the image upload if present
        $postImagePath = null;
        if ($request->hasFile('post_image')) {
            // Store image and get the path
            $postImage = $request->file('post_image');
            $postImagePath = $postImage->store('images/posts', 'public'); // Store the image in storage/app/public/images/posts
        }

        // Create the post with the image path
        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'post_image' => $postImagePath, // Save image path in database
        ]);

        // Attach selected tags if any
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403); // If the post does not belong to the authenticated user, abort
        }
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403); // Ensure the post belongs to the authenticated user
        }

        $categories = Category::all(); // Get all categories
        $tags = Tag::all(); // Get all tags
        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403); // Ensure the post belongs to the authenticated user
        }

        // Validate incoming data
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array|exists:tags,id',
            'post_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image
        ]);

        // Handle the file upload for the post image if present
        if ($request->hasFile('post_image')) {
            // Delete old image if it exists
            if ($post->post_image && Storage::exists('public/' . $post->post_image)) {
                Storage::delete('public/' . $post->post_image);
            }

            // Upload new image
            $postImage = $request->file('post_image');
            $postImagePath = $postImage->store('images/posts', 'public'); // Store in public disk
            $post->update([
                'post_image' => $postImagePath, // Update image path in the database
            ]);
        }

        // Update post content
        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
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
            abort(403); // Ensure the post belongs to the authenticated user
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
