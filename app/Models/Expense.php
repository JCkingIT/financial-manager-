<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Expense extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'category_id',
        'description'
    ];

    protected $casts = [
        'amount' => MoneyCast::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function items()
    {
        return $this->hasMany(ExpenseItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($expense) {
            if ($expense->items->isNotEmpty()) {
                $expense->amount = $expense->items->sum('cost');
            }
        });
    }

    public function audits(): MorphMany
    {
        return $this->morphMany(Audit::class, 'auditable');
    }
}
