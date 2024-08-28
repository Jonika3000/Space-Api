<?php

namespace Database\Factories;

use App\Enums\CommentStatusEnum;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->sentence,
            'user_id' => User::factory(),
            'post_id' => Post::factory(),
            'status' => CommentStatusEnum::Verified->value,
            'parent_id' => null,
        ];
    }
}
