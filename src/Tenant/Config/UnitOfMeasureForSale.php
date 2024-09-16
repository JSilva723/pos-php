<?php

declare(strict_types=1);

namespace Tenant\Config;

enum UnitOfMeasureForSale: int
{
    case GRAM = 1;
    case MILILITER = 2;
    case CENTIMETER = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::GRAM => 'Gram',
            self::MILILITER => 'Mililiter',
            self::CENTIMETER => 'Centimeter',
        };
    }
}
