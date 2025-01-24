<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enums\StateLoanFee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanFee extends Model
{
    protected $fillable = [
        'loan_id',
        'amount',
        'expiration_date',
        'state',
    ];

    protected $casts = [
        'state' => StateLoanFee::class,
        'amount' => MoneyCast::class
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }
}
