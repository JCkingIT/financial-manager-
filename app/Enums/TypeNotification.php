<?php

namespace App\Enums;

enum TypeNotification: string
{
    case Alert = 'alerta';
    case Reminder = 'recordatorio';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
