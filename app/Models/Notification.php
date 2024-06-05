<?php

namespace App\Models;

use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Broadcasting\PrivateChannel;


class Notification extends Model
{
	use HasFactory;
    // use BroadcastsEvents;


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

//    public function broadcastOn(string $event): array
//     {
//         return [$this, new PrivateChannel('user.'.$this->id)];
//     }
}
