<?php

declare(strict_types=1);

namespace Tenant\Services;

use Exception;
use Symfony\Contracts\Translation\TranslatorInterface;

use function is_numeric;

class ValidateParams
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {}

    public function validatedInt(string $value, string $property): int
    {
        $this->validate($value, $property);

        return (int) $value;
    }

    public function validatedFloat(string $value, string $property): float
    {
        $this->validate($value, $property);

        return (float) $value;
    }

    private function validate(string $value, string $property): void
    {
        if (!$value || !is_numeric($value)) {
            $translatedProperty = $this->translator->trans('validation.property.' . $property);
            $message = $this->translator->trans('validation.number_required', ['property' => $translatedProperty]);

            throw new Exception($message);
        }
    }
}
