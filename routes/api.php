<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () {
	Route::middleware('guest')->group(function () {
		Route::post('/register', 'register')->name('register');
		Route::post('/login', 'login')->name('login')->middleware(['ensure-exists', 'ensure-guest-verified']);
		Route::post('/forgot-password', 'forgot_password')->name('password.email');
		Route::post('/reset-password', 'reset_password')->name('password.update');
		Route::get('/email/verify', 'verification_notice')->name('verification.notice');
		Route::get('/email/verify/{id}/{hash}', 'verification_verify')->name('verification.verify');
	});

	Route::get('/logout', 'logout')->name('logout')->middleware(['auth']);
	Route::post('/email/verification-notification', 'verification_send')->middleware(['throttle:6,1'])->name('verification.send');
	Route::get('/check-auth', 'check_auth')->name('check_auth_state');

	Route::get('/auth/redirect', function () {
		return response()->json([
			'url'=> Socialite::driver('google')->redirect()->getTargetUrl(),
		]);
	});
	Route::get('/auth/callback', function () {
		try {
			$user = Socialite::driver('google')->user();
		} catch (Exception $e) {
			return $e;
		}

		// check if they're an existing user
		$existing = User::where('email', $user->email)->first();
		if ($existing) {
			// log the user in
			auth()->login($existing);
		} else {
			// create a new user
			$newUser = new User;
			$newUser->name = $user->name;
			$newUser->email = $user->email;
			$newUser->google_id = $user->id;
			$newUser->save();
			auth()->login($newUser);
		}

		return response()->json([
			'user' => auth()->user(),
		]);
	});
});
