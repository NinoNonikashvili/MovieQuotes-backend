<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuoteNotificationResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'quote_id'                         => $this->quote->id,
			'notification_id'                  => $this->id,
			'notification_author_name'         => $this->user->name,
			'notification_author_image'        => User::find($this->user->id) ? User::find($this->user->id)->getFirstMediaUrl('users') : '',
			'action'                           => $this->type,
			'create_at'                        => $this->created_at,
			'seen'                             => $this->seen,
		];
	}
}
