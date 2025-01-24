<?php

namespace App\Enums;

enum ActionAudit: string
{
    case Pending = 'crear';
    case Update = 'actualizar';
    case Eliminar = 'eliminar';
    case Visualize = 'visualizar';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
