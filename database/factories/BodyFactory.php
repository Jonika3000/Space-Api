<?php

namespace Database\Factories;

use App\Enums\BodiesTypeEnum;
use App\Models\Galaxy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Body>
 */
class BodyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->text(25),
            'type' => fake()->randomElement(BodiesTypeEnum::cases())->value,
            'description' => fake()->paragraph(3, true),
            'image_path' => UploadedFile::fake()->image('body.jpg', 900, 320)->store('bodies', 'public'),
            'galaxy_id' => Galaxy::factory()
        ];
    }
}
