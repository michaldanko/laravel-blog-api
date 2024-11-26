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
     *
     * @OA\Get(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     description="Display a collection of posts.",
     *     @OA\Response(
     *         response=200,
     *         description="List of posts."
     *     )
     * )
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
     *
     * @OA\Post(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="Create a new post",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "content", "author_id"},
     *             @OA\Property(property="title", type="string", example="Post title"),
     *             @OA\Property(property="content", type="string", example="Post content"),
     *             @OA\Property(property="author_id", type="integer", example="1"),
     *             @OA\Property(property="categories", type="array", @OA\Items(type="integer", example="1"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1"),
     *             @OA\Property(property="title", type="string", example="Post title"),
     *             @OA\Property(property="content", type="string", example="Post content"),
     *             @OA\Property(property="author_id", type="integer", example="1"),
     *             @OA\Property(property="categories", type="array", @OA\Items(type="integer", example="1")),
     *             @OA\Property(property="created_at", type="string", example="2021-01-01T00:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", example="2021-01-01T00:00:00.000000Z")
     *         )
     *     ),
     * )
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
     *
     * @OA\Get(
     *     path="/api/posts/{postId}",
     *     tags={"Posts"},
     *     description="Display a specific post.",
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(type="integer", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post details."
     *     )
     * )
     */
    public function show($postId)
    {
        $post = Post::with('categories', 'author')->findOrFail($postId);

        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/api/posts/{postId}",
     *     tags={"Posts"},
     *     summary="Update a post",
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(type="integer", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "content", "author_id"},
     *             @OA\Property(property="title", type="string", example="Post title"),
     *             @OA\Property(property="content", type="string", example="Post content"),
     *             @OA\Property(property="author_id", type="integer", example="1"),
     *             @OA\Property(property="categories", type="array", @OA\Items(type="integer", example="1"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1"),
     *             @OA\Property(property="title", type="string", example="Post title"),
     *             @OA\Property(property="content", type="string", example="Post content"),
     *             @OA\Property(property="author_id", type="integer", example="1"),
     *             @OA\Property(property="categories", type="array", @OA\Items(type="integer", example="1")),
     *             @OA\Property(property="created_at", type="string", example="2021-01-01T00:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", example="2021-01-01T00:00:00.000000Z")
     *         )
     *     ),
     * )
     */
    public function update(PostRequest $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $post->update($request->all());

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/api/posts/{postId}",
     *     tags={"Posts"},
     *     summary="Delete a post",
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(type="integer", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post #1 was deleted successfully.")
     *         )
     *     ),
     * )
     */
    public function destroy($postId)
    {
        Post::destroy($postId);

        return response()->json(['message' => "Post #$postId was deleted successfully."]);
    }
}
