<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::factory()
            ->count(2)
            ->hasComments(3, function (array $attributes, Post $post) {
                return [
                    'post_id' => $post->id,
                    'user_id' => User::factory(),
                ];
            })
            ->hasPostImages(3, function (array $attributes, Post $post) {
                return [
                    'post_id' => $post->id,
                ];
            })
            ->create();
    }
}
