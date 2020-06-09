<?php

namespace PodPoint\I18n\Facades;

use Illuminate\Support\Facades\Facade;
use PodPoint\I18n\CurrencyCode;

/**
 * @method static string toFormat($value, string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en')
 * @method static string toFormatFromInt(int $value, string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en')
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
