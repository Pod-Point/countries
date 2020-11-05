<?php

namespace PodPoint\I18n\Facades;

use PodPoint\I18n\CurrencyCode;
use Illuminate\Support\Facades\Facade;

/**
 * // phpcs:disable
 * @method static string toFormat($value, string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en')
 * @method static string toFormatFromInt(int $value, string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en')
 * @method static string toStandardFormat(float $value, string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en')
 * @method static string getFormatToMinorUnitWhenApplicable(int $value, string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en')
 * @method static string getSymbol(string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en')
 * // phpcs:enable
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
