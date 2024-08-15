<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;

/**
 * @OA\Info(title="Post API", version="1.0")
 */
class PostController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:sanctum', except: ['index', 'show', 'posts_by_user']),
        ];
    }

    /**
     * @OA\Post(
     *     path="/api/posts/",
     *     summary="Create a new post",
     *     description="Create a new post. Requires authentication.",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "content", "body_id"},
     *             @OA\Property(property="title", type="string", example="My New Post"),
     *             @OA\Property(property="content", type="string", example="This is the content of the post."),
     *             @OA\Property(property="body_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="My New Post"),
     *             @OA\Property(property="content", type="string", example="This is the content of the post."),
     *             @OA\Property(property="body_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'body_id' => ['required', 'integer'],
        ]);

        try {
            $post = Post::create($request->all() + ['user_id' => Auth::id()]);
        } catch(\Illuminate\Database\QueryException $ex){
            return response()->json('Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($post, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     description="Get a post by ID",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Post not found")
     * )
     */
    public function show($id)
    {
        $post = Post::with('user', 'body')->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($post);
    }

     /**
     * @OA\Get(
     *     path="/api/posts/",
      *    tags={"Posts"},
     *     description="Get a list of posts",
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index()
    {
        $posts = Post::with('body', 'user')->get();

        return response()->json($posts);
    }

    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     description="Update an existing post",
     *     security={{"sanctum":{}}},
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"title", "content", "body_id"},
     *                 @OA\Property(property="title", type="string", example="Updated Post Title"),
     *                 @OA\Property(property="content", type="string", example="This is the updated content of the post."),
     *                 @OA\Property(property="body_id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Post updated successfully"),
     *     @OA\Response(response=400, description="Invalid input data"),
     *     @OA\Response(response=404, description="Post not found"),
     *     @OA\Response(response=403, description="Unauthorized action")
     * )
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post){
            return response()->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }

        if(Auth::id() != $post->id){
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'body_id' => ['required', 'integer'],
        ]);

        $post->update($request->all() + ['user_id' => Auth::id()]);

        return response()->json($post);
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     description="Delete a post by ID",
     *     security={{"sanctum":{}}},
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Post ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Post deleted successfully"),
     *     @OA\Response(response=404, description="Post not found"),
     *     @OA\Response(response=403, description="Unauthorized action")
     * )
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        if(!$post){
            return response()->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }

        if(Auth::id() != $post->id){
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/user/{userId}",
     *     description="Get a list of posts from user",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *          name="userId",
     *          in="path",
     *          description="User id",
     *          required=true,
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function posts_by_user($userId)
    {
        $posts = Post::where('user_id',$userId)->get();

        if(!$posts){
            return response()->json(['message' => 'Posts not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($posts);
    }
}
