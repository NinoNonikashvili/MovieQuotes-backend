<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
	Route::middleware('guest')->group(function () {
		Route::post('/register', 'register')->name('register');
		Route::post('/login', 'login')->name('login')->middleware('verified');
	});
	Route::post('/logout', 'logout')->name('logout')->middleware(['auth:sanctum', 'verified']);

	Route::get('/email/verify', 'verification_notice')->middleware('auth:sanctum')->name('verification.notice');
	Route::get('/email/verify/{id}/{hash}', 'verification_verify')->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

	Route::post('/email/verification-notification', 'verification_send')->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
});
