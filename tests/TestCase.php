<?php

namespace Tests;

use App\Models\Genre;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\Movie;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
	public User $user;

	public Model $movie;

	public Quote $quote;

	public Genre $genre;

	public $image;

	use RefreshDatabase, WithFaker;

	protected function setUp(): void
	{
		parent::setUp();
		$this->artisan('migrate:fresh', ['--seed' => true, '--seeder' => 'TestDatabaseSeeder']);
		$users = User::where('name', 'ninja')->get();

		$this->user = $users[0];
		$movies = Movie::where('title->en', 'Bamby')->get();
		$this->movie = $movies[0];

		$quotes = Quote::where('movie_id', $this->movie->id)->get();
		$this->quote = $quotes[0];

		$this->genre = Genre::find(1);

		Storage::fake('public');

		$this->image = UploadedFile::fake()->image('testImage.jpg');
	}
}
