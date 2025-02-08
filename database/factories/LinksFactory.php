<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Links>
 */
class LinksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->words(2, true);
        $slug = Str::slug($title, '-');

        return [ 
            'title' => $title,
            'slug' => $slug,
            'description' => $this->faker->paragraph(5, false),
            'url' => $this->faker->url,
            'category_id' => $this->faker->numberBetween(1, 8),
            'created_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
