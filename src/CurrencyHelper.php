<?php

namespace PodPoint\I18n;

use NumberFormatter;

class CurrencyHelper extends Helper
{
    /**
     * Return a value in the given currency and locale format.
     *
     * @param float $value
     * @param string $locale
     * @param string $currencyCode
     *
     * @return string
     */
    public function toFormat(float $value, string $locale = 'en', string $currencyCode = 'GBP')
    {
        $countryHelper = new CountryHelper($this->config);

        $userCountry = $countryHelper->findBy('locale', $locale);
        $userSystemLocale = $userCountry['systemLocale'];

        $formatter = new NumberFormatter(
            $userSystemLocale,
            NumberFormatter::CURRENCY
        );

        return $formatter->formatCurrency($value, $currencyCode);
    }
}
