<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieRequest;
use App\Http\Resources\GenreResource;
use App\Http\Resources\MovieBilingualResource;
use App\Http\Resources\MovieResource;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class MovieController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request): JsonResponse
	{
		$query = Movie::where('user_id', Auth::id())->orderBy('id', 'desc');
		if ($request->has('search')) {
			$query->where('title', 'LIKE', '%' . $request->input('search') . '%');
		}
		$movies = $query->cursorPaginate(4);
		return response()->json([
			'data'     => MovieResource::collection($movies),
			'next_url' => $movies->nextPageUrl(),
		]);
	}

	public function getGenres(): JsonResponse
	{
		$genres = QueryBuilder::for(Genre::class)
		->get();
		return response()->json([
			'data'=> GenreResource::collection($genres),
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(MovieRequest $request): Response
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
		if ($movie) {
			$movie->addMediaFromRequest('image')->toMediaCollection('movies');
		}
		$movie->genres()->attach($request->input('genre'));
		return response()->noContent();
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Movie $movie): JsonResponse
	{
		return response()->json([
			'data' => new MovieBilingualResource($movie),
		]);
	}

	public function single(Movie $movie): JsonResponse
	{
		return response()->json([
			'data' => new MovieResource($movie),
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Movie $movie): Response
	{
		if ($request->has('name_en')) {
			$movie->setTranslation('title', 'en', $request->input('name_en'));
		}
		if ($request->has('name_ge')) {
			$movie->setTranslation('title', 'ge', $request->input('name_ge'));
		}
		if ($request->has('director_en')) {
			$movie->setTranslation('director', 'en', $request->input('director_en'));
		}
		if ($request->has('director_ge')) {
			$movie->setTranslation('director', 'ge', $request->input('director_ge'));
		}
		if ($request->has('description_en')) {
			$movie->setTranslation('description', 'en', $request->input('description_en'));
		}
		if ($request->has('description_ge')) {
			$movie->setTranslation('description', 'ge', $request->input('description_ge'));
		}
		if ($request->has('year')) {
			$movie->year = $request->input('year');
		}
		if ($request->has('image')) {
			if ($media = $movie->getFirstMedia('movies')) {
				$media->delete();
			}
			$movie->addMediaFromRequest('image')->toMediaCollection('movies');
		}
		$movie->save();

		return response()->noContent();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Movie $movie): Response
	{
		if ($media = $movie->getFirstMedia('movies')) {
			$media->delete();
		}
		$movie->delete();
		return response()->noContent();
	}
}
