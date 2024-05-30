<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\UserController;
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
	Route::post('/update-profile', [UserController::class, 'update'])->middleware(['auth']);
	Route::get('/updated-user', [UserController::class, 'show'])->middleware((['auth']));

	Route::middleware('auth:sanctum')->group(function () {
		Route::controller(MovieController::class)->group(function () {
			Route::get('/movies', 'index')->name('get-movies');
		});
		Route::controller(QuoteController::class)->group(function () {
			Route::post('/store-quote', 'store')->name('store-quote');
		});
	});
});
