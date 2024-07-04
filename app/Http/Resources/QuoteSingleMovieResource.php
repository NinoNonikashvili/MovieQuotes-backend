<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteSingleMovieResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'                    => $this->id,
			'quote_image'           => $this->getFirstMediaUrl('quotes'),
			'quote'                 => $this->quote,
			'comment_number'        => $this->whenLoaded('notifications', function ($notifications) {
				return $notifications->where('quote_id', $this->id)
										->where('type', 'comment')
										->count();
			}),
			'react_number'=> $this->whenLoaded('notifications', function ($notifications) {
				return $notifications->where('quote_id', $this->id)
									->where('type', 'heart')
									->count();
			}),
		];
	}
}
