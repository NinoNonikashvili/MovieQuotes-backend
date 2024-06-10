<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationUpdated implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * Create a new event instance.
	 */
	public function __construct(
		public $quote_id,
		public $not_id,
		public $user_name,
		public $user_image,
		public $type,
		public $created_at,
		public $seen,
		public $channel
	) {
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return array<int, \Illuminate\Broadcasting\Channel>
	 */
	public function broadcastOn()
	{
		return new Channel('notification' . $this->channel);
	}

	public function broadcastAs()
	{
		return 'notification-update';
	}

	public function broadcastWith()
	{
		
		return [
			'quote_id'                         => $this->quote_id,
			'notification_id'                  => $this->not_id,
			'notification_author_name'         => $this->user_name,
			'notification_author_image'        => $this->user_image,
			'action'                           => $this->type,
			'create_at'                        => $this->created_at,
			'seen'                             => $this->seen,
		];
	}
}
