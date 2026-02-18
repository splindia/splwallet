<?php

namespace App\Enums;

enum UserRole: string
{
    case Merchant = 'merchant';
    case Employee = 'employee';
    case Reseller = 'reseller';
    case Admin = 'admin';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
