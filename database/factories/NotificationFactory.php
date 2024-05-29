<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as GeorgianFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public $types = ['heart', 'comment'];

	public function definition(): array
	{
		$type = $this->types[random_int(0, 1)];
		return [
			'quote_id'=> 1,
			'user_id' => 1,
			'type'    => $type,
			'comment' => $type === 'comment' ? ['en' => $this->faker->sentence(), 'ge' => GeorgianFactory::create('ka_GE')->realText(15)] : null,
			'seen'    => false,
		];
	}
}
