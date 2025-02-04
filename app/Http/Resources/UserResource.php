<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		// add user avatar from media

		return [
			'id'        => $this->id,
			'name'      => $this->name,
			'email'     => $this->email,
			'image'     => $this? $this->getFirstMediaUrl('users') : '',
			'google_id' => $this->google_id,
		];
	}
}
