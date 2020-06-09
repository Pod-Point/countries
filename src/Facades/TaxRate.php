<?php

namespace PodPoint\I18n\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static float get(string $countryCode)
 * @method static float calculate(int|float $netPrice, string $countryCode, string|null $postalCode = null, bool|null $company = null, string|null $type = null)
 * @method static float exclude(int|float $netPrice, string $countryCode, string|null $postalCode = null, bool|null $company = null, string|null $type = null)
 */
class TaxRate extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'i18n.taxrate';
    }
}
