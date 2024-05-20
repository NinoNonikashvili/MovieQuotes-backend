<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\CustomEmailVerificationRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Exceptions;

class AuthController extends Controller
{
	public function checkAuth(): JsonResponse
	{
		return response()->json([
			'user' => auth()->user(),
		]);
	}

	public function register(UserRegisterRequest $request): Response | JsonResponse
	{
		//create user

		$user = User::create([
			'name'     => $request['name'],
			'email'    => $request['email'],
			'password' => $request['password'],
		]);
		if ($user) {
			//send verification email
			event(new Registered($user));
			return response()->json([
				'message_key' => 'USER_REGISTERED_SUCCESSFULLY',
				'data'        => [
					'email' => $request['email'],
				],
			]);
		} else {
			//emai || name already exists is handled by form request validations
			return response()->noContent(500);
		}
	}

	public function login(UserLoginRequest $request): JsonResponse
	{
		$credentials = ['password' => $request['password']];
		if ($request->has('email')) {
			$credentials['email'] = $request['email'];
			//check if registered bu gmail
			$user = User::where('email', $request->input('email'))->get();
			if (count($user) && !$user[0]->password) {
				return response()->json([
					'message_key'   => 'LOGIN_FAILED',
					'message'       => __('validation.gmail_login'),
				], 403);
			}
		} else {
			$credentials['name'] = $request['name'];
		}

		if (Auth::attempt($credentials, $request['remember'])) {
			return response()->json([
				'message_key'   => 'LOGIN_SUCCESS',
				'user_data'     => new UserResource(auth()->user()),
			]);
		} else {
			return response()->json([
				'message_key' => 'LOGIN_FAILED',
			], 404);
		}
	}

	public function logout(Request $request): Response
	{
		Auth::logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();
		return response()->noContent();
	}

	public function verificationNotice(): JsonResponse
	{
		return response()->json([
			'message_key' => 'EMAIL_MUST_BE_VERIFIED',
		], 403);
	}

	public function verificationVerify(CustomEmailVerificationRequest $request): JsonResponse
	{
		if (!$request->hasValidSignature(true)) {
			return response()->json([
				'message_key'   => 'LINK_EXPIRED',
				'email'         => User::find($request->route('id'))->email,
			], 403);
		}
		$request->fulfill();

		return response()->json([
			'message_key'   => 'EMAIL_VERIFIED',
		]);
	}

	public function verificationSend(Request $request): Response | JsonResponse
	{
		if ($request->has('email')) {
			$user = User::where('email', $request->input('email'))->get();
			if (count($user)) {
				$user[0]->sendEmailVerificationNotification();
				return response()->json([
					'message_key' => 'VERIFICATION_LINK_SENT',
				]);
			}
		}

		return response()->noContent(404);
	}

	public function forgotPassword(Request $request): Response | JsonResponse
	{
		$status = Password::sendResetLink(
			$request->only('email')
		);

		return $status === Password::RESET_LINK_SENT
					? response()->noContent(200)
					: response()->json([
						'message' => 'not found ',
					], 404);
	}

	public function resetPassword(Request $request): Response
	{
		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function (User $user, string $password) {
				$user->forceFill([
					'password' => Hash::make($password),
				])->setRememberToken(Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			}
		);

		return $status === Password::PASSWORD_RESET
					? response()->noContent(200)
					: response()->noContent(500);
	}

	public function authRedirect(): JsonResponse
	{
		return response()->json([
			'url'=> Socialite::driver('google')->redirect()->getTargetUrl(),
		]);
	}

	public function authCallback(): Response | JsonResponse
	{
		try {
			$googleUser = Socialite::driver('google')->user();
		} catch (Exceptions $e) {
			return response()->noContent(500);
		}

		$user = User::updateOrCreate(
			[
				'google_id' => $googleUser->id,
			],
			[
				'name'              => 'user',
				'email'             => $googleUser->email,
				'email_verified_at' => now(),
			]
		);
		auth()->login($user);
		// check if they're an existing user
		// $existing = User::where('email', $user->email)->first();
		// if ($existing) {
		// 	// log the user in
		// 	auth()->login($existing);
		// } else {
		// 	// create a new user
		// 	$newUser = new User;
		// 	$newUser->name = 'user';
		// 	$newUser->email = $user->email;
		// 	$newUser->google_id = $user->id;
		// 	$newUser->email_verified_at = Carbon::now();
		// 	$newUser->save();
		// 	auth()->login($newUser);
		// }

		return response()->json([
			'user' => auth()->user(),
		]);
	}
}
