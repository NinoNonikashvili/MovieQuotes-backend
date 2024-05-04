<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Validator;

class CustomEmailVerificationRequest extends EmailVerificationRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}



	/**
	 * Fulfill the email verification request.
	 *
	 * @return void
	 */
	public function fulfill()
	{
		$user = User::find($this->route('id'));
		if (!$user->hasVerifiedEmail()) {
			$user->markEmailAsVerified();

			event(new Verified($user));
		}
	}
}
