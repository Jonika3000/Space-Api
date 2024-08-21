<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureIsAuthorMiddleware;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * @OA\Tag(
 *     name="Comments",
 *     description="Operations related to comments"
 * )
 */
class CommentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:sanctum', except: ['index', 'show', 'get_comments_by_post']),
            new Middleware(middleware: EnsureIsAuthorMiddleware::class, except: ['index', 'store', 'show', 'get_comments_by_post'])

        ];
    }
    public function index()
    {

    }

    /**
     * @OA\Post(
     *     path="/api/comments",
     *     tags={"Comments"},
     *     summary="Create a new comment",
     *     description="Store a newly created comment resource",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="content", type="string"),
     *             @OA\Property(property="post_id", type="integer"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comment created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
     *     )
     * )
     */
    public function store(StoreCommentRequest $request)
    {
        $post = $request->user()->comments()->create($request->validated());

        return new CommentResource($post->load('user'));
    }

    /**
     * @OA\Get(
     *     path="/api/comments/{id}",
     *     tags={"Comments"},
     *     summary="Get a specific comment",
     *     description="Retrieve a specific comment by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the comment to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment details",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     )
     * )
     */
    public function show(Comment $comment)
    {
        return new CommentResource($comment->load('user', 'parent', 'post'));
    }

    /**
     * @OA\Put(
     *     path="/api/comments/{id}",
     *     tags={"Comments"},
     *     summary="Update a specific comment",
     *     description="Update a specific comment resource",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the comment to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="content", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comment updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     )
     * )
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());

        return new CommentResource($comment->load('user', 'parent', 'post'));
    }

    /**
     * @OA\Delete(
     *     path="/api/comments/{id}",
     *     tags={"Comments"},
     *     summary="Delete a specific comment",
     *     description="Delete a specific comment resource",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the comment to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Comment deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comment not found"
     *     )
     * )
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/api/comments/post/{postId}",
     *     tags={"Comments"},
     *     summary="Get comments by post",
     *     description="Retrieve all comments for a specific post",
     *     @OA\Parameter(
     *         name="postId",
     *         in="path",
     *         required=true,
     *         description="ID of the post",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of comments for the post",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Comment")
     *             ),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     )
     * )
     */
    public function get_comments_by_post($postId)
    {
        $comments = Comment::where('post_id', $postId)->with('user', 'parent', 'post')->paginate(10);

        return CommentResource::collection($comments);
    }
}
