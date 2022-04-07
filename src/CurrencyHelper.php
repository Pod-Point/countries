<?php

namespace PodPoint\I18n;

use NumberFormatter;

class CurrencyHelper extends LocalizedHelper
{
    /**
     * Cached formatters.
     *
     * @var array
     */
    protected $cachedFormatters = [];

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
        return $this
            ->getDefaultFormatter($locale)
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
        return $this
            ->getFixedPrecisionFormatter($locale, $precision)
            ->formatCurrency($value, $currencyCode);
    }

    /**
     * Get the formatter cache key.
     *
     * @param  array  $params
     * @return string
     */
    protected function getFormatterCacheKey(array $params): string
    {
        return hash('sha256', serialize($params));
    }

    /**
     * Gets a formatter or creates and returns it if not already.
     *
     * @param  string  $key
     * @param  callable  $createHandler
     * @return NumberFormatter
     */
    protected function getFormatter(string $key, callable $createHandler): NumberFormatter
    {
        if (array_key_exists($key, $this->cachedFormatters)) {
            return $this->cachedFormatters[$key];
        }

        return $this->cachedFormatters[$key] = $createHandler();
    }

    /**
     * Create the default formatter for the given locale.
     *
     * @param  string  $locale
     * @return NumberFormatter
     */
    protected function getDefaultFormatter(string $locale): NumberFormatter
    {
        return $this->getFormatter(
            $this->getFormatterCacheKey(func_get_args()),
            function () use ($locale) {
                $formatter = $this->getBaseFormatter(
                    $this->getSystemLocale($locale)
                );

                /*
                 * NumberFormatter will round up with 2 decimals only by default.
                 * Sometimes we can display up to 6 decimals of the monetary unit (ex: £0.106544) for energy prices.
                 */
                $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 6);

                return $formatter;
            }
        );
    }

    /**
     * Create a basic number formatter for the given locale in the given style.
     *
     * @param  string  $locale
     * @param  string|null  $pattern
     * @param  int|null  $style
     * @return NumberFormatter
     */
    protected function getBaseFormatter(string $locale, ?string $pattern = null, ?int $style = null): NumberFormatter
    {
        return new NumberFormatter(
            $locale,
            $style ?? NumberFormatter::CURRENCY,
            $pattern
        );
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
            $this->getFormatterCacheKey(func_get_args()),
            function () use ($locale, $currencyCode) {
                return $this->getBaseFormatter(
                    $this->getSystemLocale($locale) . "@currency=$currencyCode"
                );
            }
        );
    }

    /**
     * Create a number formatter with a given precision.
     *
     * @param  string  $locale
     * @param  int|null  $precision
     * @return NumberFormatter
     */
    protected function getFixedPrecisionFormatter(string $locale, ?int $precision): NumberFormatter
    {
        return $this->getFormatter(
            $this->getFormatterCacheKey(func_get_args()),
            function () use ($locale, $precision) {
                $formatter = $this->getBaseFormatter($this->getSystemLocale($locale));

                if (is_int($precision)) {
                    $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $precision);
                    $formatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, $precision);
                }

                return $formatter;
            }
        );
    }

    /**
     * Create a number formatter using a pattern.
     *
     * @param  string  $locale
     * @param  string  $pattern
     * @return NumberFormatter
     */
    protected function getPatternedFormatter(string $locale, string $pattern): NumberFormatter
    {
        return $this->getFormatter(
            $this->getFormatterCacheKey(func_get_args()),
            function () use ($locale, $pattern) {
                $formatter = $this->getBaseFormatter($this->getSystemLocale($locale), $pattern);

                $formatter->setPattern($pattern);

                return $formatter;
            }
        );
    }
}
