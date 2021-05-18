<?php

use PodPoint\I18n\CurrencyCode;
use PodPoint\I18n\Facades\CurrencyHelper;

if (! function_exists('moneyFormat')) {

    /**
     * Return a value in the given currency formatted for the given locale.
     *
     * @param float|int $value
     * @param string $currencyCode
     * @param string $locale
     *
     * @return string
     */
    function moneyFormat($value, string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en'): string
    {
        return CurrencyHelper::toFormat($value, $currencyCode, $locale);
    }
}

if (! function_exists('moneyFormatFromInt')) {

    /**
     * Transform an integer representing a decimal currency value (penny, cents...) into a monetary formatted string
     * with the right currency symbol and the right localised format for the parameters respectively given.
     *
     * @param int $value
     * @param string $currencyCode
     * @param string $locale
     *
     * @return string
     */
    function moneyFormatFromInt(int $value, string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en'): string
    {
        return CurrencyHelper::toFormatFromInt($value, $currencyCode, $locale);
    }
}

if (! function_exists('getCurrencySymbol')) {

    /**
     * Return a currency symbol formatted in the right locale.
     *
     * @param string $currencyCode
     * @param string $locale
     *
     * @return string
     */
    function getCurrencySymbol(string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en'): string
    {
        return CurrencyHelper::getSymbol($currencyCode, $locale);
    }
}

if (! function_exists('getFormatToMinorUnitWhenApplicable')) {

    /**
     * Return a currency symbol formatted in the right locale.
     *
     * @param int $value
     * @param string $currencyCode
     * @param string $locale
     *
     * @return string
     */
    function getFormatToMinorUnitWhenApplicable(int $value, string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en'): string
    {
        return CurrencyHelper::formatToMinorUnitWhenApplicable($value, $currencyCode, $locale);
    }
}
