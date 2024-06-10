<?php

use App\Events\NotificationUpdated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;

describe('broadcast test', function () {
	it('dispatches an event if notification is added', function () {
        // Broadcast::shouldReceive('event')->once()->with(NotificationUpdated::class);
    
        Event::fake();
        Queue::fake();
    
        $this->actingAs($this->user)->post(route('add-quote-notification'), [
            'quote_id' => '1',
            'user_id' => '1',
            'type' => 'react'
        ]);

        Event::assertDispatched(NotificationUpdated::class);
	});
});
