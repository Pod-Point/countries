<?php

namespace PodPoint\I18n;

use NumberFormatter;

class CurrencyHelper extends LocalizedHelper
{
    /**
     * Number Formatters created per locale.
     *
     * @var array
     */
    protected $localizedFormatters = [];

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
        $formatter = $this->getFormatter($locale);

        /*
         * NumberFormatter will round up with 2 decimals only by default.
         * Sometimes we can display up to 6 decimals of the monetary unit (ex: £0.106544) for energy prices.
         */
        $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 6);

        return $formatter->formatCurrency($value, $currencyCode);
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
            $formatter = $this->getFormatter($locale);

            $formatter->setPattern($pattern);

            return $formatter->formatCurrency($value, $currencyCode);
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
        $formatter = $this->getFormatter($locale);

        return $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
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
        $currencyCode = CurrencyCode::POUND_STERLING,
        string $locale = 'en',
        $precision = null
    ): string {
        $formatter = $this->getFormatter($locale);

        if (is_int($precision)) {
            $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $precision);
            $formatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, $precision);
        }

        return $formatter->formatCurrency($value, $currencyCode);
    }

    /**
     * Get an instance of a localized Formatter.
     *
     * @param  string  $locale
     * @return NumberFormatter
     */
    protected function getFormatter(string $locale): NumberFormatter
    {
        return $this->localizedFormatters[$locale]
            ?? $this->setFormatter(
                $locale,
                new NumberFormatter($this->getSystemLocale($locale), NumberFormatter::CURRENCY)
            );
    }

    /**
     * Set an instance of a localized Formatter.
     *
     * @param  string  $locale
     * @param  NumberFormatter  $formatter
     * @return NumberFormatter
     */
    public function setFormatter(string $locale, NumberFormatter $formatter): NumberFormatter
    {
        return $this->localizedFormatters[$locale] = $formatter;
    }
}
