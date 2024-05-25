<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'name'                  => 'required|min:3|max:15|regex:/^[a-z0-9]*$/',
			'email'                 => 'required|unique:users|email',
			'password'              => 'required|min:8|max:15|regex:/^[a-z0-9]*$/',
			'password_confirmation' => 'required|same:password',
		];
	}
}
