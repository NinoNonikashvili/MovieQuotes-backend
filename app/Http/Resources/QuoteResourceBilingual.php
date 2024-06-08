<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResourceBilingual extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'quote_text'      => $this->getTranslations('quote'),
			'quote_image'     => $this->getFirstMediaUrl('images'),
			'comment_number'  => $this->notifications->where('quote_id', $this->id)
													->where('type', 'comment')
													->count(),
			'react_number'=> $this->notifications->where('quote_id', $this->id)
													->where('type', 'heart')
													->count(),
			'comments'=> CommentResource::collection($this->notifications->where('type', 'comment')),
		];
	}
}
