<?php

namespace PodPoint\I18n;

use NumberFormatter;

class NumberHelper extends LocalizedHelper
{
    /**
     * Return a number formatted for the given locale. Specify precision to define the exact number of decimal digits.
     *
     * @param  float|int  $value
     * @param  string  $locale
     * @param  int|null  $precision
     * @return string
     */
    public function toFormat($value, string $locale = 'en', int $precision = null): string
    {
        if (is_null($precision)) {
            return $this->getDefaultFormatter($locale)->format($value);
        }

        return $this->getFixedPrecisionFormatter($locale, $precision)->format($value);
    }

    /**
     * Return the formatter style used by the number formatter.
     *
     * @return int
     */
    protected function getFormatterStyle(): int
    {
        return NumberFormatter::DEFAULT_STYLE;
    }
}
