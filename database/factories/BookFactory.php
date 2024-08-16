<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'title' => $this->faker->title,
            'author_id' => \App\Models\Author::factory(),
            'category_id' => \App\Models\Category::factory(),
            'isbn' => $this->faker->isbn13,
            'published_date' => $this->faker->date,
            'copies_available' => $this->faker->numberBetween(1, 10),
            'cover_image' => $this->faker->imageUrl(),

        ];
    }
}
