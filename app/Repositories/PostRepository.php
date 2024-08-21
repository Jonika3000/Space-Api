<?php

namespace App\Repositories;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    public function __construct(Post $model)
    {
        parent::__construct($model);
    }

    public function index()
    {
        return Post::with('body', 'user', 'images')->paginate(10);
    }

    public function store(FormRequest $request): Model
    {
      return $request->user()->posts()->create($request->validated());
    }

    public function show(Model $model): Model
    {
        return $model->load('user', 'body', 'comments', 'images');
    }

    public function posts_by_user(int $user_id)
    {
        return Post::where('user_id', $user_id)->paginate(10);
    }
}
