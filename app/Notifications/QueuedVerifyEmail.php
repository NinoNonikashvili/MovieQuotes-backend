<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class QueuedVerifyEmail extends Notification implements ShouldQueue
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 */
	public function __construct()
	{
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
		$url = URL::temporarySignedRoute(
			'verification.verify',
			Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
			[
				'id'   => $notifiable->getKey(),
				'hash' => sha1($notifiable->getEmailForVerification()),
			]
		);
		$front_url = str_replace(env('APP_URL') . '/api/email/verify/', env('FRONTEND_URL') . '/email/verify/en/', $url);
		return (new MailMessage)
		->theme('custom')
		->subject(__('email.btn_verify_account'))
		->greeting(__('email.greeting'), $notifiable->name)
		->line(__('email.text_email_verify'))
		->action(__('email.btn_verify_account'), $front_url)
		->line(__("email.text_link_plan_b"))
		->line($front_url)
		->line(__('email.text_contact_us'))	
		->line(__('email.signature'))
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
