<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserController;
use App\Models\Quote;
use Illuminate\Support\Facades\Route;

Route::middleware('lang')->group(function () {
	Route::controller(AuthController::class)->group(function () {
		Route::middleware('guest')->group(function () {
			Route::post('/register', 'register')->name('register');
			Route::post('/login', 'login')->name('login')->middleware(['ensure-exists', 'ensure-guest-verified']);
			Route::post('/forgot-password', 'forgotPassword')->name('password.email');
			Route::post('/reset-password', 'resetPassword')->name('password.update');
			Route::get('/email/verify', 'verificationNotice')->name('verification.notice');
			Route::get('/email/verify/{id}/{hash}', 'verificationVerify')->name('verification.verify');
		});

		Route::get('/logout', 'logout')->name('logout')->middleware(['auth']);
		Route::post('/email/verification-notification', 'verificationSend')->middleware(['throttle:6,1'])->name('verification.send');
		Route::get('/check-auth', 'checkAuth')->name('check_auth_state');
		Route::get('/auth/redirect', 'authRedirect')->name('auth.redirect');
		Route::get('/auth/callback', 'authCallback')->name('auth.callback');
	});
	Route::post('/update-profile', [UserController::class, 'update'])->middleware(['auth'])->name('update-profile');
	Route::get('/updated-user', [UserController::class, 'show'])->middleware((['auth']));

	Route::middleware('auth:sanctum')->group(function () {
		Route::controller(MovieController::class)->group(function () {
			Route::get('/movies', 'index')->name('get-movies');
			Route::get('/movies/{movie}', 'show')->name('get-single-movie-billingual');
			Route::get('movies/single/{movie}', 'single')->name('get-single-movie');
			Route::get('/genres', 'getGenres')->name('get-genres');
			Route::post('/add-movie', 'store')->name('add-movie');
			Route::post('/edit-movie/{movie}', 'update')->name('edit-movie');
			Route::get('/delete-movie/{movie}', 'destroy')->name('delete-movie');
		});
		Route::controller(QuoteController::class)->group(function () {
			Route::post('/store-quote', 'store')->name('store-quote');
			Route::get('quotes', 'index')->name('get-quotes');
			Route::post('single-movie-quotes', 'singleMovieQuotes')->name('single-movie-quotes');
			Route::get('comments/{quote}', 'getComments')->name('get-quote-comments');
			Route::get('/delete-quote/{quote}', 'destroy')->name('delete-quote');
			Route::post('/update-quote/{quote}', 'update')->name('update-quote');
			Route::get('/quotes/{quote}', 'show')->name('get-single-quote');
			Route::post('/add-quote-notification', 'addQuoteNotification')->name('add-quote-notification');
			Route::post('/remove-quote-heart', 'removeQuoteHeart')->name('remove-quote-heart');
			Route::get('/get-notifications', 'getNotifications')->name('get-notifications');
			Route::post('/set-notification-seen', 'setNotificationSeen')->name('set-notification-seen');
			Route::get('/set-all-notifications-seen', 'setAllNotificationsSeen')->name('set-all-notifications-seen');
		});
	});
});

