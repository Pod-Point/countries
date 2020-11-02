<?php

namespace PodPoint\I18n;

use Illuminate\Support\Arr;
use NumberFormatter;

class CurrencyHelper extends LocalizedHelper
{
    /*
     * NumberFormatter will round up with 2 decimals only by default.
     * Sometimes we can display up to 6 decimals of the monetary unit (ex: £0.106544) for energy prices.
     */
    const MAX_PRECISION = 6;

    /**
     * Return a value in the given currency formatted for the given locale.
     *
     * @param float|int $value
     * @param string $currencyCode
     * @param string $locale
     *
     * @param int $precision
     * @return string
     */
    public function toFormat(
        $value,
        string $currencyCode = CurrencyCode::POUND_STERLING,
        string $locale = 'en',
        $precision = self::MAX_PRECISION
    ): string {
        $formatter = new NumberFormatter(
            $this->getSystemLocale($locale),
            NumberFormatter::CURRENCY
        );

        $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $precision);

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
    public function toFormatFromInt(
        int $value,
        string $currencyCode = CurrencyCode::POUND_STERLING,
        string $locale = 'en'
    ): string {
        return $this->toFormat($value / 100, $currencyCode, $locale);
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
    public function formatToMinorUnitWhenApplicable(
        int $value,
        string $currencyCode = CurrencyCode::POUND_STERLING,
        string $locale = 'en'
    ): string {
        $pattern = $this->getMinorUnitPattern($locale);

        if ($value <= $this->getMinorUnitEnd($locale) && $pattern) {
            $formatter = new NumberFormatter(
                $this->getSystemLocale($locale),
                NumberFormatter::CURRENCY
            );

            $formatter->setPattern($pattern);

            return $formatter->formatCurrency($value, $currencyCode);
        }

        return $this->toFormatFromInt($value, $currencyCode, $locale);
    }

    /**
     * Return a currency symbol formatted in the right locale.
     *
     * @param string $locale
     * @param string $currencyCode
     *
     * @return string
     */
    public function getSymbol(string $currencyCode = CurrencyCode::POUND_STERLING, string $locale = 'en'): string
    {
        $formatter = new NumberFormatter(
            "{$this->getSystemLocale($locale)}@currency={$currencyCode}",
            NumberFormatter::CURRENCY
        );

        return $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
    }

    public function toStandardFormat(
        float $value,
        $currencyCode = CurrencyCode::POUND_STERLING,
        string $locale = 'en'
    ): string {
        $precision = Arr::get(
            $this->config->get('currencies'),
            "{$currencyCode}.precision",
            self::MAX_PRECISION
        );

        return $this->toFormat($value, $currencyCode, $locale, $precision);
    }
}
