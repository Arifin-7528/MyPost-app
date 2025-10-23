<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['image', 'video']);
        $filePath = $type === 'image' ? 'images/sample.jpg' : 'videos/sample.mp4';

        return [
            'user_id' => User::factory(),
            'type' => $type,
            'file_path' => $filePath,
            'caption' => fake()->sentence(),
        ];
    }
}
