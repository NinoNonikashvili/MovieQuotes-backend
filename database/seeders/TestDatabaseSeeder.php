<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$user = User::create([
			'name'              => 'ninja',
			'email'             => 'ninja@gmail.com',
			'email_verified_at' => now(),
		]);
		$movie = Movie::create([
			'title'       => ['en'=> 'Bamby', 'ge'=>'ბემბი'],
			'description' => 'little bamby',
			'director'    => 'some guy',
			'year'        => '1945',
			'user_id'     => $user->id,
		]);
		$quote = Quote::create([
			'quote'    => ['en' => 'lovely animal', 'ge' => 'საყვარელი ცხოველი'],
			'movie_id' => $movie->id,
		]);
		$genre = Genre::factory(1)->create();
	}
}
