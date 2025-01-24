<?php

namespace App\Enums;

enum TypeCategory: string
{
    case ICOME = 'Ingreso';
    case EXPENSE = 'Gasto';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
