<?php

namespace PodPoint\I18n\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string|null getCountryCodeFromLocale(string $locale)
 * @method static array|null findByLocale(string $locale)
 */
class CountryHelper extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'country.helper';
    }
}
