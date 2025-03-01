<?php

namespace App\Enums;

enum StateLoanFee: string
{
    case Pending = 'pendiente';
    case Paid = 'Pagado';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
