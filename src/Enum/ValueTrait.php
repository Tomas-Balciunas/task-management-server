<?php

namespace App\Enum;

trait ValueTrait
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}