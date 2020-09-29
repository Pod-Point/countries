<?php

namespace PodPoint\I18n\Facades;

use PodPoint\I18n\CurrencyCode;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string toFormat($value, string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en')
 * @method static string toFormatFromInt(int $value, string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en')
 * @method static string formatToMinorUnitWhenApplicable(int $value, string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en')
 * @method static string getSymbol(string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en')
 */
class CurrencyHelper extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'currency.helper';
    }
}
