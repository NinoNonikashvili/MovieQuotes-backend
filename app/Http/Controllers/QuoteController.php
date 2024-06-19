<?php

namespace App\Http\Controllers;

use App\Events\NotificationUpdated;
use App\Http\Requests\AddNotificationRequest;
use App\Http\Requests\QuoteRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\QuoteNotificationResource;
use App\Http\Resources\QuoteResource;
use App\Http\Resources\QuoteResourceBilingual;
use App\Http\Resources\QuoteSingleMovieResource;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request): JsonResponse
	{
		$query = Quote::with(['notifications', 'movie'])->orderBy('id', 'desc');

		if ($request->has('search')) {
			if ($request->input('search')[0] === '@') {
				$query->whereHas('movie', function ($query) use ($request) {
					$query->where('title', 'LIKE', '%' . substr($request->input('search'), 1) . '%');
				});
			} elseif ($request->input('search')[0] === '#') {
				$query->where('quote', 'LIKE', '%' . substr($request->input('search'), 1) . '%');
			} else {
				$query->where('quote', 'LIKE', '%' . $request->input('search') . '%')
				->orWhereHas('movie', function ($query) use ($request) {
					$query->where('title', 'LIKE', '%' . $request->input('search') . '%');
				});
			}
		} 
		$quotes = $query->cursorPaginate(9);
		
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
			$quote->addMediaFromRequest('image')->toMediaCollection('quotes');
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
			$quote->setTranslation('quote', 'en', $request->input('quote_en'));
		}
		if ($request->has('quote_ge')) {
			$quote->setTranslation('quote', 'ge', $request->input('quote_ge'));
		}
		if ($request->has('image')) {
			if ($media = $quote->getFirstMedia('quotes')) {
				$media->delete();
			}
			$quote->addMediaFromRequest('image')->toMediaCollection('quotes');
		}
		$quote->save();
		return response()->noContent();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Quote $quote): Response
	{
		if ($media = $quote->getFirstMedia('quotes')) {
			$media->delete();
		}
		$quote->delete();

		return response()->noContent();
	}

	public function addQuoteNotification(AddNotificationRequest $request): Response
	{
		$notification = Notification::create($request->validated());
		//add row in reactions table
		$user = User::find(auth()->user()->id);
		if ($user) {
			$notification->type === 'react' ? $user->reactedQuotes()->attach($notification->quote_id) : (
				$notification->type === 'unreact' ? $user->reactedQuotes()->detach($notification->quote_id) : ''
			);
		}

		event(new NotificationUpdated(
			$notification->quote->id,
			$notification->id,
			$notification->user->name,
			User::find($notification->user->id)->getFirstMediaUrl('users'),
			$notification->type,
			$notification->created_at,
			$notification->seen,
			$notification->quote->movie->user->id,
		));
		return response()->noContent();
	}

	public function getNotifications()
	{
		$notifications = Notification::whereHas('quote.movie.user', function ($query) {
			$query->where('id', auth()->user()->id);
		})->with(['quote.movie.user'])->orderBy('created_at', 'desc')->get();

		return  response()->json([
			'data'  => QuoteNotificationResource::collection($notifications),
		]);
	}

	public function setNotificationSeen(Request $request): Response
	{
		if ($request->input('id')) {
			$not = Notification::find($request->input('id'));
			$not->seen = true;
			$not->save();
		}
		return response()->noContent();
	}

	public function setAllNotificationsSeen(): Response
	{
		Notification::query()->update(['seen' => true]);
		return response()->noContent();
	}
}
