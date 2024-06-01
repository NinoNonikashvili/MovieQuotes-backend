<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieRequest;
use App\Http\Resources\GenreResource;
use App\Http\Resources\MovieResource;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class MovieController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$movies = QueryBuilder::for(Movie::class)
		->where('user_id', Auth::id())
		->defaultSort('-created_at')
		->get();
		return MovieResource::collection($movies);
	}

	public function getGenres()
	{
		$genres = QueryBuilder::for(Genre::class)
		->get();
		return GenreResource::collection($genres);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(MovieRequest $request)
	{
		$movie = Movie::create([
			'title' => [
				'en' => $request->input('name_en'),
				'ge' => $request->input('name_ge'),
			],
			'director' => [
				'en' => $request->input('director_en'),
				'ge' => $request->input('director_ge'),
			],
			'description' => [
				'en' => $request->input('description_en'),
				'ge' => $request->input('description_ge'),
			],
			'year'   => $request->input('year'),
			'user_id'=> $request->input('user_id'),
		]);
		if($movie){
			$movie->addMediaFromRequest('image')->toMediaCollection('movies');
		}
		$movie->genres()->attach($request->input('genre'));
		return response()->noContent();
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Movie $movie)
	{
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Movie $movie)
	{
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Movie $movie)
	{
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Movie $movie)
	{
	}
}
