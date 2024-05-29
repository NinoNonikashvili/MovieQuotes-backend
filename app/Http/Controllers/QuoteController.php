<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Http\Resources\QuoteResource;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class QuoteController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$quotes = QueryBuilder::for(Quote::class)
		->defaultSort('created_at')
		->get();
		return response()->json([
			'quotes' => QuoteResource::collection($quotes),
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
