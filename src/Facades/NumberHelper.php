<?php

namespace PodPoint\I18n\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string toFormat(float|int $value, string $locale, int $precision = null)
 */
class NumberHelper extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'number.helper';
    }
}
