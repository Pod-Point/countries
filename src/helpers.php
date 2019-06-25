<?php

use PodPoint\I18n\Facades\CurrencyHelper;
use PodPoint\I18n\CurrencyCode;

if (!function_exists('moneyFormat')) {

    /**
     * Return a value in the given currency formatted for the given locale.
     *
     * @param float|int $value
     * @param string $currencyCode
     * @param string $locale
     *
     * @return string
     */
    function moneyFormat($value, string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en')
    {
        return CurrencyHelper::toFormat($value, $currencyCode, $locale);
    }
}

if (!function_exists('moneyFormatFromCents')) {

    /**
     * Return a value in the given currency formatted for the given locale from integer amount.
     *
     * @param int $value
     * @param string $currencyCode
     * @param string $locale
     *
     * @return string
     */
    function moneyFormatFromInt(int $value, string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en')
    {
        return CurrencyHelper::toFormatFromInt($value, $currencyCode, $locale);
    }
}

if (!function_exists('getCurrencySymbol')) {

    /**
     * Return a currency symbol formatted in the right locale.
     *
     * @param string $currencyCode
     * @param string $locale
     *
     * @return string
     */
    function getCurrencySymbol(string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en')
    {
        return CurrencyHelper::getSymbol($currencyCode, $locale);
    }
}
