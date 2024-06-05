<?php

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
	return (int) $user->id === (int) $id;
});

Broadcast::channel('notification.{notificationId}', function (User $user, int $notificationId) {
    return $user->id === Notification::findOrNew($notificationId)->quote->movie->user_id;
});
