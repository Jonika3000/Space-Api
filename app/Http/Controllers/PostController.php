<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Post;
use App\Models\PostImage;
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
     *     description="Create a new post. Requires authentication. Optionally, images can be uploaded with the post.",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "content", "body_id"},
     *                 @OA\Property(property="title", type="string", example="My New Post"),
     *                 @OA\Property(property="content", type="string", example="This is the content of the post."),
     *                 @OA\Property(property="body_id", type="integer", example=1),
     *                 @OA\Property(
     *                     property="images[]",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         format="binary"
     *                     ),
     *                     description="Array of image files"
     *                 )
     *             )
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
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(
     *                 property="images",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="path", type="string", example="storage/images/example.jpg")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request - invalid input data or image upload failed"
     *     )
     * )
     */

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'body_id' => ['required', 'integer'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ]);

        try {
            $post = Post::create($request->all() + ['user_id' => Auth::id()]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('images', 'public');

                $image = Image::create(['path' => $path]);

                PostImage::create([
                    'post_id' => $post->id,
                    'image_id' => $image->id,
                ]);
            }
        }
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
        $post = Post::with('user', 'body', 'comments', 'postImages')->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }

        $images = [];
        foreach ($post['postImages'] as $postImage) {
            $image = Image::where('id', $postImage->image_id)->first();
            $images[] = $image;
        }
        $post->images = $images;

        unset($post->postImages);

        return response()->json($post);
    }

     /**
     * @OA\Get(
     *     path="/api/posts/",
      *    tags={"Posts"},
     *     description="Get a list of posts",
      *          @OA\Parameter(
      *          name="page",
      *          in="query",
      *          description="Page number for pagination",
      *          required=false,
      *          @OA\Schema(type="integer")
      *      ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index()
    {
        $posts = Post::with('body', 'user', 'postImages')->paginate(10);

        foreach ($posts as $post) {
            $images = [];
            foreach ($post['postImages'] as $image) {
                $imagePost = Image::where('id', $image->image_id)->first();
                $images[] = $imagePost;
            }
            $post->images = $images;
            unset($post['postImages']);
        }

        return response()->json($posts);
    }

    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     description="Update an existing post, including adding or updating images.",
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
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"title", "content", "body_id"},
     *                 @OA\Property(property="title", type="string", example="Updated Post Title"),
     *                 @OA\Property(property="content", type="string", example="This is the updated content of the post."),
     *                 @OA\Property(property="body_id", type="integer", example=1),
     *                 @OA\Property(
     *                     property="images[]",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         format="binary",
     *                         description="Only image files are allowed (jpg, jpeg, png, gif)"
     *                     ),
     *                     description="Array of image files to be attached to the post"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Updated Post Title"),
     *             @OA\Property(property="content", type="string", example="This is the updated content of the post."),
     *             @OA\Property(property="body_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(
     *                 property="images",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="path", type="string", example="storage/images/example.jpg")
     *                 ),
     *                 description="Array of image details associated with the post"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input data"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized action"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post){
            return response()->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }

        if(Auth::id() != $post->user_id){
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'body_id' => ['required', 'integer'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ]);

        $post->update($request->only(['title', 'content', 'body_id']) + ['user_id' => Auth::id()]);

        if ($request->hasFile('images')) {
            PostImage::where('post_id', $post->id)->delete();
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('images', 'public');
                $image = Image::create(['path' => $path]);
                PostImage::create([
                    'post_id' => $post->id,
                    'image_id' => $image->id,
                ]);
            }
        }

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
