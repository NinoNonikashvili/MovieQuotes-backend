<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuoteRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'movie_id' => 'required',
			'quote_en' => 'required|regex:/^[a-zA-Z0-9.,!?"()@$%:*\-\s]+$/',
			'quote_ge' => 'required|regex:/^[ა-ჰ0-9.,!?"()@$%:*\-\s]+$/',
			'image'    => 'required|image',
		];
	}
}
