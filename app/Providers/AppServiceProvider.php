<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
			//get correct language and replace 'en' by it in the url
			$front_url = str_replace(env('APP_URL') . '/api/email/verify/', env('FRONTEND_URL') . '/email/verify/en/', $url);
			return (new MailMessage)
				->subject($url)
				->line('TEST: Click the button below to verify your email address.')
				->action('Verify Email Address', $front_url);
		});

		ResetPassword::toMailUsing(function ($notifiable, $token) {
			$url = url(route('password.update', [
				'token' => $token,
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
		});
	}
}
