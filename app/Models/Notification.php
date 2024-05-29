<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Notification extends Model
{
	use HasFactory;

	use HasTranslations;

	protected $fillable = [
		'quote_id',
		'user_id',
		'type',
		'comment',
		'seen',
	];
    
    public $translatable = ['comment'];

   public function user():BelongsTo
   {
    return $this->belongsTo(User::class);
   }
   public function quote():BelongsTo
   {
    return $this->belongsTo(Quote::class);
   }

}
