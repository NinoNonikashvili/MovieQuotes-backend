<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\InteractsWithMedia;

class Movie extends Model implements HasMedia
{
	use HasFactory;

	use InteractsWithMedia;

	use HasTranslations;

	protected $fillable = [
		'title',
		'description',
		'year',
		'director',
		'user_id',
	];

	public $translatable = ['title', 'description', 'director'];

	public function quotes(): HasMany
	{
		return $this->hasMany(Quote::class);
	}

	public function genres(): BelongsToMany
	{
		return $this->belongsToMany(Genre::class, 'genre_movie');
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function registerMediaCollections(): void
	{
		$this->addMediaCollection('images')
		->useDisk('movies')
		->singleFile();
	}
}
