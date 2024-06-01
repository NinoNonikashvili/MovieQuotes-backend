<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovieRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'user_id'        => 'required',
			'year'           => 'required',
			'name_en'        => 'required|regex:/^[a-zA-Z0-9.,!?"()@$%:*\-\s]+$/',
			'name_ge'        => 'required|regex:/^[ა-ჰ0-9.,!?"()@$%:*\-\s]+$/',
			'director_en'    => 'required|regex:/^[a-zA-Z0-9.,!?"()@$%:*\-\s]+$/',
			'director_ge'    => 'required|regex:/^[ა-ჰ0-9.,!?"()@$%:*\-\s]+$/',
			'description_en' => 'required|regex:/^[a-zA-Z0-9.,!?"()@$%:*\-\s]+$/',
			'description_ge' => 'required|regex:/^[ა-ჰ0-9.,!?"()@$%:*\-\s]+$/',
			'image'          => 'required|image',
			'genre'          => 'required',
		];
	}
}
