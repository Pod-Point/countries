<?php

namespace PodPoint\I18n;

use NumberFormatter;

class CurrencyHelper extends LocalizedHelper
{
    /**
     * Return a value in the given currency formatted for the given locale.
     *
     * @param  float|int  $value
     * @param  string  $currencyCode
     * @param  string  $locale
     * @return string
     *
     * @deprecated toStandardFormat should be used.
     */
    public function toFormat(
        $value,
        string $currencyCode = CurrencyCode::POUND_STERLING,
        string $locale = 'en'
    ): string {
        /*
         * NumberFormatter will round up with 2 decimals only by default.
         * Sometimes we can display up to 6 decimals of the monetary unit (ex: £0.106544) for energy prices.
         */
        return $this
            ->getDefaultFormatter($locale, 6)
            ->formatCurrency($value, $currencyCode);
    }

    /**
     * Transform an integer representing a decimal currency value (penny, cents...) into a monetary formatted string
     * with the right currency symbol and the right localised format for the parameters respectively given.
     *
     * @param  int  $value
     * @param  string  $currencyCode
     * @param  string  $locale
     * @return string
     */
    public function toFormatFromInt(
        int $value,
        string $currencyCode = CurrencyCode::POUND_STERLING,
        string $locale = 'en'
    ): string {
        return $this->toStandardFormat($value / 100, $currencyCode, $locale);
    }

    /**
     * Transform an integer representing a decimal currency value (penny, cents...) into a monetary formatted string
     * with the right currency symbol and the right localised format for the parameters respectively given.
     *
     * @param  int  $value
     * @param  string  $currencyCode
     * @param  string  $locale
     * @return string
     */
    public function formatToMinorUnitWhenApplicable(
        int $value,
        string $currencyCode = CurrencyCode::POUND_STERLING,
        string $locale = 'en'
    ): string {
        $pattern = $this->getMinorUnitPattern($locale);

        if ($value <= $this->getMinorUnitEnd($locale) && $pattern) {
            return $this
                ->getPatternedFormatter($locale, $pattern)
                ->formatCurrency($value, $currencyCode);
        }

        return $this->toFormatFromInt($value, $currencyCode, $locale);
    }

    /**
     * Return a currency symbol formatted in the right locale.
     *
     * @param  string  $locale
     * @param  string  $currencyCode
     * @return string
     */
    public function getSymbol(string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en'): string
    {
        return $this
            ->getSymbolFormatter($locale, $currencyCode)
            ->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
    }

    /**
     * Return a value in the given currency format for the given currency code and locale.
     *
     * @param  float  $value
     * @param  string  $currencyCode
     * @param  string  $locale
     * @param  int|null  $precision  Number of decimals to show. If null is given, it will take the default currency
     *                               precision
     * @return string
     */
    public function toStandardFormat(
        float $value,
        string $currencyCode = CurrencyCode::POUND_STERLING,
        string $locale = 'en',
        int $precision = null
    ): string {
        if (is_null($precision)) {
            return $this->getDefaultFormatter($locale)->formatCurrency($value, $currencyCode);
        }

        return $this
            ->getFixedPrecisionFormatter($locale, $precision)
            ->formatCurrency($value, $currencyCode);
    }

    /**
     * Create a number formatter for retrieving symbols.
     *
     * @param  string  $locale
     * @param  string  $currencyCode
     * @return NumberFormatter
     */
    protected function getSymbolFormatter(string $locale, string $currencyCode): NumberFormatter
    {
        return $this->getFormatter(
            $this->getFormatterCacheKey(__FUNCTION__, func_get_args()),
            function () use ($locale, $currencyCode) {
                return $this->getBaseFormatter(
                    $this->getSystemLocale($locale) . "@currency=$currencyCode"
                );
            }
        );
    }

    /**
     * Return the formatter style used by the number formatter.
     *
     * @return int
     */
    protected function getFormatterStyle(): int
    {
        return NumberFormatter::CURRENCY;
    }
}
