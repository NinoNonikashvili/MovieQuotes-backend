<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as GeorgianFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quote>
 */
class QuoteFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'quote'    => ['en' => $this->faker->sentence(), 'ge' => GeorgianFactory::create('ka_GE')->realText(15)],
			'movie_id' => 1,
		];
	}
}
