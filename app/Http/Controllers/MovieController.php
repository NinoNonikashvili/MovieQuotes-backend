<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
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
		->get();
		return MovieResource::collection($movies);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
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
