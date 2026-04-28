<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * GET /api/posts
     * List all posts
     */
    public function index()
    {
        $posts = Post::latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $posts,
            'total'   => $posts->count(),
        ]);
    }

    /**
     * POST /api/posts
     * Create a new post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'  => 'required|string|max:255',
            'body'   => 'required|string',
            'author' => 'nullable|string|max:100',
        ]);

        $post = Post::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully!',
            'data'    => $post,
        ], 201);
    }

    /**
     * GET /api/posts/{id}
     * Show a single post
     */
    public function show(Post $post)
    {
        return response()->json([
            'success' => true,
            'data'    => $post,
        ]);
    }

    /**
     * PUT /api/posts/{id}
     * Update a post
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'  => 'sometimes|string|max:255',
            'body'   => 'sometimes|string',
            'author' => 'nullable|string|max:100',
        ]);

        $post->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully!',
            'data'    => $post->fresh(),
        ]);
    }

    /**
     * DELETE /api/posts/{id}
     * Delete a post
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully!',
        ]);
    }
}
