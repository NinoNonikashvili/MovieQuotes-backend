<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as GeorgianFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'title'       => ['en' => $this->faker->title(), 'ge' => GeorgianFactory::create('ka_GE')->realText(15)] ,
			'description' => ['en' => $this->faker->sentence(), 'ge' => GeorgianFactory::create('ka_GE')->realText(15)],
			'year'        => fake()->year(),
			'director'    => ['en' => $this->faker->name(), 'ge' => GeorgianFactory::create('ka_GE')->realText(15)],
			'user_id'     => 1,
		];
	}
}
