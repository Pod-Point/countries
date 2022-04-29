<?php

namespace PodPoint\I18n;

use Illuminate\Config\Repository;
use NumberFormatter;

abstract class LocalizedHelper extends Helper
{
    /**
     * Instance of the CountryHelper.
     *
     * @var CountryHelper
     */
    protected $countryHelper;

    /**
     * Cached formatters.
     *
     * @var array
     */
    protected $cachedFormatters = [];

    /**
     * Return the formatter style used by the number formatter.
     *
     * @return int
     */
    abstract protected function getFormatterStyle(): int;

    /**
     * @param  Repository  $config
     */
    public function __construct(Repository $config)
    {
        parent::__construct($config);

        $this->countryHelper = new CountryHelper($this->config);
    }

    /**
     * Return system locale from locale. (en => en_GB.UTF-8).
     *
     * @param  string  $locale
     * @param  bool  $fallback
     * @return string|null
     */
    protected function getSystemLocale(string $locale, bool $fallback = true): ?string
    {
        $country = $this->countryHelper->findByLocale($locale);

        return $country['systemLocale'] ?? ($fallback ? $this->getFallbackSystemLocale() : null);
    }

    /**
     * Return minor unit pattern from locale. (en => #.##p).
     *
     * @param  string  $locale
     * @return string|null
     */
    protected function getMinorUnitPattern(string $locale): ?string
    {
        $country = $this->countryHelper->findByLocale($locale);

        return $country['minorUnitPattern'] ?? null;
    }

    /**
     * Return the last minor unit from locale. (en => 99).
     *
     * @param  string  $locale
     * @return int|null
     */
    protected function getMinorUnitEnd(string $locale): ?int
    {
        $country = $this->countryHelper->findByLocale($locale);

        return $country['minorUnitEnd'] ?? null;
    }

    /**
     * Returns the fallback system Locale or null if there's no match.
     *
     * @return string|null
     */
    protected function getFallbackSystemLocale(): ?string
    {
        $country = $this->countryHelper->findByLocale($this->config->get('app.fallback_locale'));

        return $country['systemLocale'] ?? null;
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

        $this->cachedFormatters[$key] = $createHandler();

        return $this->cachedFormatters[$key] = $createHandler();
    }

    /**
     * Get the formatter cache key.
     *
     * @param  string  $method
     * @param  array  $params
     * @return string
     */
    protected function getFormatterCacheKey(string $method, array $params): string
    {
        return hash('sha256', $method . serialize($params));
    }

    /**
     * Create a basic number formatter for the given locale in the given style.
     *
     * @param  string  $locale
     * @return NumberFormatter
     */
    protected function getBaseFormatter(string $locale): NumberFormatter
    {
        return new NumberFormatter($locale, $this->getFormatterStyle());
    }

    /**
     * Create a number formatter with a fixed given precision.
     *
     * @param  string  $locale
     * @param  int  $precision
     * @return NumberFormatter
     */
    protected function getFixedPrecisionFormatter(string $locale, int $precision): NumberFormatter
    {
        return $this->getDefaultFormatter($locale, $precision, $precision);
    }

    /**
     * Create the default formatter for the given locale. The precision can be variable based on the max and min values.
     * If no precision is specified, the default one for the locale and NumberFormatter style will be used.
     *
     * @param  string  $locale
     * @param  int|null  $maxPrecision
     * @param  int|null  $minPrecision
     * @return NumberFormatter
     */
    protected function getDefaultFormatter(
        string $locale,
        int $maxPrecision = null,
        int $minPrecision = null
    ): NumberFormatter {
        return $this->getFormatter(
            $this->getFormatterCacheKey(__FUNCTION__, func_get_args()),
            function () use ($locale, $maxPrecision, $minPrecision) {
                $formatter = $this->getBaseFormatter($this->getSystemLocale($locale));

                if (! is_null($maxPrecision)) {
                    $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $maxPrecision);
                }

                if (! is_null($minPrecision)) {
                    $formatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, $minPrecision);
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
            $this->getFormatterCacheKey(__FUNCTION__, func_get_args()),
            function () use ($locale, $pattern) {
                $formatter = $this->getBaseFormatter($this->getSystemLocale($locale));

                $formatter->setPattern($pattern);

                return $formatter;
            }
        );
    }
}
