<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddNotificationRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'quote_id'=> 'required',
			'user_id' => 'required',
			'type'    => 'required',
			'comment' => "exclude_if:type,react,unreact|required"
		];
	}
}
