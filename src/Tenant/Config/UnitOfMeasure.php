<?php

declare(strict_types=1);

namespace Tenant\Config;

enum UnitOfMeasure: int
{
    case UNIT = 0;
    case GRAM = 1;
    case MILIMETERS = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::UNIT => 'Unit',
            self::GRAM => 'Gram',
            self::MILIMETERS => 'Milliliters',
        };
    }
}
