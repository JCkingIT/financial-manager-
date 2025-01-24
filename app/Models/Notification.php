<?php

namespace App\Models;

use App\Enums\TypeNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'type',
        'read'
    ];

    protected $casts = [
        'type' => TypeNotification::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
