<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\InteractsWithMedia;

class Quote extends Model implements HasMedia
{
	use HasFactory;

	use InteractsWithMedia;

	use HasTranslations;

	protected $fillable = [
		'movie_id',
		'quote',
	];

	public $translatable = ['quote'];

	public function movie(): BelongsTo
	{
		return $this->belongsTo(Movie::class);
	}

	public function notifications(): HasMany
	{
		return $this->hasMany(Notification::class);
	}
	public function user():BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function registerMediaCollections(): void
	{
		$this->addMediaCollection('images')
		->useDisk('quotes')
		->singleFile();
	}
}
