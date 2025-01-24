<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enums\StateLoan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'term',
        'state',
        'dues_paid',
    ];

    protected $casts = [
        'state' => StateLoan::class,
        'amount' => MoneyCast::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function loanfees(): HasMany
    {
        return $this->hasMany(LoanFee::class);
    }

    public function audits(): MorphMany
    {
        return $this->morphMany(Audit::class, 'auditable');
    }
}
