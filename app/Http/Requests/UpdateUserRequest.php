<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'name'                  => 'sometimes|required|min:3|max:15|regex:/^[a-z0-9]*$/',
			'password'              => 'sometimes|required|min:8|max:15|regex:/^[a-z0-9]*$/',
			'password_confirmation' => 'sometimes|required|same:password',
			'image'                 => 'sometimes|required|image',
		];
	}
}
