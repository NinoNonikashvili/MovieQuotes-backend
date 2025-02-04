<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'          => $this->id,
			'title'       => $this->title,
			'description' => $this->description,
			'year'        => $this->year,
			'director'    => $this->director,
			'genres'      => GenreResource::collection($this->genres),
			'quote_num'   => $this->quotes->count(),
			'image'       => $this ? $this->getFirstMediaUrl('movies') :'',
		];
	}
}
