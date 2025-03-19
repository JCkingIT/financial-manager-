<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
    protected $fillable = [
        'description',
        'cost',
        'expense_id',
    ];

    protected $cast = [
        'cost' => MoneyCast::class
    ];

    public function expese()
    {
        return $this->belongsTo(Expense::class);
    }
}
