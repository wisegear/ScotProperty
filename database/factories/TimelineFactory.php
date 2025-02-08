<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Timeline>
 */
class TimelineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence, // Generates a random title
            'date' => $this->faker->dateTimeBetween('1933-01-01', '1945-12-31')->format('Y-m-d'), // Restricting date range
            'text' => $this->faker->paragraph, // Generates a random paragraph of text
            'published' => $this->faker->boolean(80), // 80% chance of being true
            'created_at' => now(), // Current timestamp
            'updated_at' => now(), // Current timestamp
        ];
    }
}
