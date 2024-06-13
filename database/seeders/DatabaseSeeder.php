<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		User::factory(1)->create();

		Movie::factory()->hasAttached(Genre::factory(2))->create(
			['user_id' => 1]
		);
		Quote::factory(5)->create();
		Notification::factory(4)->create();
	}
}
