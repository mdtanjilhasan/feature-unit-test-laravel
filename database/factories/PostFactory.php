<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $randomUser = User::all()->random();
        return [
            'title' => fake()->sentence(10),
            'user_id' => $randomUser->id,
            'short_description' => fake()->text(100),
            'full_details' => fake()->realText(500),
            'image' => fake()->imageUrl()
        ];
    }
}
