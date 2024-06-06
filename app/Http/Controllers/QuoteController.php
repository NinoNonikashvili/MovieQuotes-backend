<?php

namespace App\Http\Controllers;

use App\Events\NotificationUpdated;
use App\Http\Requests\AddNotificationRequest;
use App\Http\Requests\QuoteRequest;
use App\Http\Requests\RemoveHeartRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\QuoteResource;
use App\Http\Resources\QuoteResourceBilingual;
use App\Http\Resources\QuoteSingleMovieResource;
use App\Models\Notification;
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
		->cursorPaginate(4);
		return response()->json([
			'quotes'   => QuoteResource::collection($quotes),
			'next_url' => $quotes->nextPageUrl(),
		]);
	}

	public function singleMovieQuotes(Request $request): JsonResponse
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

	public function comments(Quote $quote): JsonResponse
	{
		$comments = $quote->notifications()->where('type', 'comment');
		return response()->json([
			'data' => CommentResource::collection($comments),
		]);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Quote $quote): JsonResponse
	{
		return response()->json([
			'data' => new QuoteResourceBilingual($quote),
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Quote $quote): Response
	{
		if ($request->has('quote_en')) {
			$quote->settTanslation('quote', 'en', $request->input('quote_en'));
		}
		if ($request->has('quote_ge')) {
			$quote->setTranslation('quote', 'ge', $request->input('quote_ge'));
		}
		if ($request->has('image')) {
			if ($media = $quote->getFirstMedia('images')) {
				$media->delete();
			}
			$quote->addMediaFromRequest('image')->toMediaCollection('images');
		}
		$quote->save();
		return response()->noContent();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Quote $quote): Response
	{
		if ($media = $quote->getFirstMedia('images')) {
			$media->delete();
		}
		$quote->delete();

		return response()->noContent();
	}

	public function addQuoteNotification(AddNotificationRequest $request): Response
	{
		$notification = Notification::create($request->validated());
		$id = $notification->id;
		$notification_author = $notification->user;
		$quote_author = $notification->quote->movie->user;
		$type = $notification->type;
		$comment = $notification->comment;
		event(new NotificationUpdated(
			$id,
			$notification_author,
			$quote_author,
			$type,
			'add',
			$comment
		));
		return response()->noContent();
	}

	public function removeQuoteHeart(RemoveHeartRequest $request): Response
	{
		$notification = Notification::where('user_id', $request->input('user_id'))
		->where('quote_id', $request->input('quote_id'))
		->where('type', 'heart')->get();
		$id = $notification[0]->id;
		$notification_author = $notification[0]->user;
		$quote_author = $notification[0]->quote->movie->user;

		event(new NotificationUpdated(
			$id,
			$notification_author,
			$quote_author,
			'heart',
			'delete',
			null
		));
		$notification[0]->delete();
		return response()->noContent();
	}
}
