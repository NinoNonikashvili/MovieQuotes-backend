<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\QueuedVerifyEmail;
use App\Notifications\QueuedResetPassword;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
	use HasFactory;

	use InteractsWithMedia;

	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'google_id',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'email_verified_at' => 'datetime',
			'password'          => 'hashed',
		];
	}

	public function sendEmailVerificationNotification()
	{
		$this->notify(new QueuedVerifyEmail);
	}

	public function sendPasswordResetNotification($token)
	{
		$this->notify(new QueuedResetPassword($token));
	}

	public function registerMediaCollections(): void
	{
		$this->addMediaCollection('users')
		->useDisk('users')
		->singleFile();
	}

	public function notifications(): HasMany
	{
		return $this->hasMany(Notification::class);
	}

	public function movies(): HasMany
	{
		return $this->hasMany(Movie::class);
	}

	public function quotes(): HasManyThrough
	{
		return $this->hasManyThrough(Quote::class, Movie::class);
	}
}
