<?php

namespace PodPoint\I18n;

use NumberFormatter;

class NumberHelper extends LocalizedHelper
{
    /**
     * Return a number formatted for the given locale.
     *
     * @param float|int $value
     * @param string $locale
     *
     * @return string
     */
    public function format($value, string $locale = 'en')
    {
        $formatter = new NumberFormatter(
            $this->getSystemLocale($locale),
            NumberFormatter::DECIMAL
        );

        return $formatter->format($value);
    }
}
