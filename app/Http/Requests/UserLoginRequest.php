<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'email'                 => 'email',
			'name'                  => 'exclude_if:email|required',
			'password'              => 'required|min:8|max:15|regex:/^[a-z0-9]*$/gm',
			'remember'              => 'required',
		];
	}
}
