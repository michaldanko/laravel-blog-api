<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Cache::remember('posts_index', 100, function () {
            return Post::with('categories', 'author')->paginate(10);
        });

        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $post = Post::create($request->all());

        if ($request->has('categories')) {
            $post->categories()->sync($request->categories);
        }

        return response()->json($post->load('categories', 'author'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($postId)
    {
        $post = Post::with('categories', 'author')->findOrFail($postId);

        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $post->update($request->all());

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($postId)
    {
        Post::destroy($postId);

        return response()->json(['message' => "Post #$postId was deleted successfully."]);
    }
}
