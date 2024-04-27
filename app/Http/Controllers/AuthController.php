<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Resources\UserResource;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
	public function register(UserRegisterRequest $request)
	{
		//create user
		$user = User::create([
			'name'     => $request['name'],
			'email'    => $request['email'],
			'password' => $request['password'],
		]);
		if ($user) {
			//send verification email
			Auth::attempt(['email' => $request['email'], 'password' => $request['password']]);
			event(new Registered($user));
			return response()->json([
				'message' => 'USER_REGISTERED_SUCCESSFULLY',
			]);
		} else {
			//emai || name already exists is handled by form request validations
			//and the error needs to be modified in appserviceprovider in extensions
			return response()->noContent(500);
		}
	}

	public function login(UserLoginRequest $request)
	{
		$credentials = ['password' => $request['password']];
		if ($request->has('email')) {
			$credentials['email'] = $request['email'];
		} else {
			$credentials['name'] = $request['name'];
		}
		//email not verified is handled by verified middleware
		//if it fails it redirects to verification.notice route which send appropriate
		//response to frontend
		if ($user = Auth::attempt($credentials, $request['remember'])) {
			return response()->json([
				'message'   => 'LOGIN_SUCCESS',
				'user_data' => new UserResource($user),
			]);
		} else {
			return response()->json([
				'message' => 'LOGIN_FAILED',
			], 404);
		}
	}

	public function logout(Request $request)
	{
		Auth::logout();
		$request->session()->invalidate()();
		$request->session()->regenerateToken();
		return response()->noContent();
	}

	public function verification_notice()
	{
		return response()->json([
			'message' => 'EMAIL_MUST_BE_VERIFIED',
		], 403);
	}

	public function verification_verify(EmailVerificationRequest $request)
	{
		$request->fulfill();

		return response()->json([
			'message'   => 'EMAIL_VERIFIED',
			'user_data' => new UserResource(auth()->user()),
		]);
	}

	public function verification_send(Request $request)
	{
		$request->user()->sendEmailVerificationNotification();

		return response()->json([
			'message' => 'VERIFICATION_LINK_SENT',
		]);
	}
}
