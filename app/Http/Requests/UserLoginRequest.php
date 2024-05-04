<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'email'                   => 'email',
			'name'                    => 'exclude_with:email|required',
			'password'                => 'required|min:8|max:15|regex:/^[a-z0-9]*$/',
			'rememberMe'              => 'required',
		];
	}
}
