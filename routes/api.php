<?php

use App\Http\Controllers\AuthController;
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

		Route::get('/auth/redirect', 'authRedirect');
		Route::get('/auth/callback', 'authCallback');
	});
});
