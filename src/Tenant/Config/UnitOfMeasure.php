<?php

declare(strict_types=1);

namespace Tenant\Config;

enum UnitOfMeasure: int
{
    case UNIT = 0;
    case KILOGRAM = 1;
    case LITER = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::UNIT => 'Unit',
            self::KILOGRAM => 'Kilogram',
            self::LITER => 'Liter',
        };
    }
}
