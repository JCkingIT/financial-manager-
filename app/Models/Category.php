<?php

namespace App\Models;

use App\Enums\TypeCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    protected $fillable = [
        'name',
        'type',
        'user_id',
        'description',
        'icon'
    ];
    protected $casts = [
        'type' => TypeCategory::class
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($categoria) {
            if (Auth::check()) {
                $categoria->user_id = Auth::id();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function icomes(): HasMany
    {
        return $this->hasMany(Icome::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function audits(): MorphMany
    {
        return $this->morphMany(Audit::class, 'auditable');
    }
}
