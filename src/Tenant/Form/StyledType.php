<?php

declare(strict_types=1);

namespace Tenant\Form;

use Symfony\Component\Form\AbstractType;

class StyledType extends AbstractType
{
    public const ROW_ATTR = 'w-full md:w-1/2';
    public const LABEL_ATTR = 'block mb-2 mt-2 text-sm font-medium text-gray-900 dark:text-white';
    public const INPUT_ATTR = 'text-md font-semibold bg-gray-200 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white';
    public const INPUT_CHECK_ATTR = 'w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800';
}
