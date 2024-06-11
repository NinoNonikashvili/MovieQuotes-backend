<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class QuoteResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'quote_id'        => $this->id,
			'author_avatar'   => $this->whenLoaded('movie') ? $this->whenLoaded('movie')->user->getFirstMediaUrl('users') : '',
			'user_avatar'     => User::find(Auth::user()->id) ? User::find(Auth::user()->id)->getFirstMediaUrl('users') : '',
			'author_name'     => $this->movie->user->name,
			'quote_text'      => $this->quote,
			'quote_image'     => $this? $this->getFirstMediaUrl('images') : '',
			'quote_year'      => $this->whenLoaded('movie')->year,
			'quote_director'  => $this->whenLoaded('movie')->director,
			'comment_number'  => $this->whenLoaded('notifications', function ($notifications) {
				return $notifications->where('quote_id', $this->id)
										->where('type', 'comment')
										->count();
			}),
			'react_number'=> $this->reactors->count(),
			'comments'    => CommentResource::collection($this->whenLoaded('notifications', function ($notifications) {
				return $notifications->where('type', 'comment');
			})),
			'has_user_loved' => $this->reactors()->where('user_id', auth()->user()->id)->exists(),
			'nots'           => $this->whenLoaded('notifications'),
		];
	}
}
