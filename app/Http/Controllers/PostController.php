<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:sanctum', except: ['index', 'show', 'posts_by_user']),
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'user_id' => ['required', 'integer'],
            'body_id' => ['required', 'integer'],
        ]);

        try {
            $post = Post::create($request->all());
        } catch(\Illuminate\Database\QueryException $ex){
            return response()->json('Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($post, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $post = Post::with('user', 'body')->find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($post);
    }

    public function index()
    {
        $posts = Post::with('body', 'user')->get();

        return response()->json($posts);
    }

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
            'user_id' => ['required', 'integer'],
            'body_id' => ['required', 'integer'],
        ]);

        $post->update($request->all());

        return response()->json($post);
    }

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

    public function posts_by_user($userId)
    {
        $posts = Post::where('user_id',$userId);

        if(!$posts){
            return response()->json(['message' => 'Posts not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($posts);
    }
}
