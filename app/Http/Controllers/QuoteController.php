<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Http\Resources\QuoteResource;
use App\Http\Resources\QuoteSingleMovieResource;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): JsonResponse
	{
		$quotes = QueryBuilder::for(Quote::class)

		->with(['notifications', 'movie'])
		->orderBy('created_at', 'desc')
		->get();
		return response()->json([
			'quotes' => QuoteResource::collection($quotes),
		]);
	}

	public function singleMovieQuotes(Request $request)
	{
		$quotes = QueryBuilder::for(Quote::class)
		->with(['notifications'])
		->orderBy('created_at', 'desc')
		->where('movie_id', $request->input('id'))
		->get();
		return response()->json([
			'quotes' => QuoteSingleMovieResource::collection($quotes),
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(QuoteRequest $request): Response
	{
		$quote = Quote::create([
			'quote' => [
				'en' => $request->input('quote_en'),
				'ge' => $request->input('quote_ge'),
			],
			'movie_id' => $request->input('movie_id'),
		]);
		if ($quote) {
			$quote->addMediaFromRequest('image')->toMediaCollection('images');
		}
		return response()->noContent();
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Quote $quote)
	{
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Quote $quote)
	{
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Quote $quote)
	{
	}
}
