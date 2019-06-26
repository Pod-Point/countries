<?php

namespace PodPoint\I18n;

use NumberFormatter;

class CurrencyHelper extends LocalizedHelper
{
    /**
     * Return a value in the given currency formatted for the given locale.
     *
     * @param float|int $value
     * @param string $currencyCode
     * @param string $locale
     *
     * @return string
     */
    public function toFormat($value, string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en')
    {
        $formatter = new NumberFormatter(
            $this->getSystemLocale($locale),
            NumberFormatter::CURRENCY
        );

        return $formatter->formatCurrency($value, $currencyCode);
    }

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
    public function toFormatFromInt(int $value, string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en')
    {
        return $this->toFormat($value / 100, $currencyCode, $locale);
    }

    /**
     * Return a currency symbol formatted in the right locale.
     *
     * @param string $locale
     * @param string $currencyCode
     *
     * @return string
     */
    public function getSymbol(string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en')
    {
        $formatter = new NumberFormatter(
            "{$this->getSystemLocale($locale)}@currency={$currencyCode}",
            NumberFormatter::CURRENCY
        );

        return $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
    }
}
