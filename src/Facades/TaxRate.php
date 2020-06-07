<?php

namespace PodPoint\I18n\Facades;

/**
 * @method static float get(string $countryCode)
 * @method static float calculate(int|float $netPrice, string $countryCode, string|null $postalCode, bool|null $company, string|null $type)
 */
class TaxRate extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'i18n.taxrate';
    }
}
