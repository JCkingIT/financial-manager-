<?php

namespace App\Enums;

enum StateLoan: string
{
    case Pending = 'pendiente';
    case Approved = 'aprobado';
    case Rejected = 'rechazado';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
