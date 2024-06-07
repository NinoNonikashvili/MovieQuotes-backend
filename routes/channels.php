<?php

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
	return (int) $user->id === (int) $id;
});

// Broadcast::channel('notification.{quote_author_id}', function (User $user, int $quote_author_id) {
//     return $user->id === $quote_author_id;
// });
