<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QueuedResetPassword extends Notification implements ShouldQueue
{
	use Queueable;

	/**
	 * The password reset token.
	 *
	 * @var string
	 */
	public $token;

	/**
	 * Create a new notification instance.
	 */
	public function __construct($token)
	{
		$this->token = $token;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @return array<int, string>
	 */
	public function via(object $notifiable): array
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 */
	public function toMail(object $notifiable): MailMessage
	{
		$url = url(route('password.update', [
			'token' => $this->token,
			'email' => $notifiable->getEmailForPasswordReset(),
		], false));
		$front_url = str_replace(env('APP_URL') . '/api/reset-password', env('FRONTEND_URL') . '/reset-password/en', $url);

		$expire = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');
		return (new MailMessage)
			->greeting('Reset your password')
			->subject('Reset Password Notification')
			->line('You are receiving this email because we received a password reset request for your account.')
			->line("this link will expire in $expire minutes")
			->action('Reset Password', $front_url)
			->salutation('  ');
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(object $notifiable): array
	{
		return [
		];
	}
}
