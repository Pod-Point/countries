<?php

namespace PodPoint\I18n;

use Illuminate\Config\Repository;
use NumberFormatter;

class CurrencyHelper extends Helper
{
    /**
     * Instance of the CountryHelper.
     *
     * @var CountryHelper
     */
    protected $countryHelper;

    /**
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        parent::__construct($config);

        $this->countryHelper = new CountryHelper($this->config);
    }

    /**
     * Return a value formatted in the right currency and locale.
     *
     * @param float $value
     * @param string $locale
     * @param string $currencyCode
     *
     * @return string
     */
    public function toFormat(float $value, string $locale = 'en', string $currencyCode = 'GBP')
    {
        $country = $this->countryHelper->findBy('locale', $locale);
        $systemLocale = $country['systemLocale'];

        $formatter = new NumberFormatter(
            $systemLocale,
            NumberFormatter::CURRENCY
        );

        return $formatter->formatCurrency($value, $currencyCode);
    }

    /**
     * Return a currency symbol formatted in the right locale.
     *
     * @param string $locale
     *
     * @return string
     */
    public function getSymbol(string $locale = 'en')
    {
        $country = $this->countryHelper->findBy('locale', $locale);
        $systemLocale = $country['systemLocale'];

        $formatter = new NumberFormatter(
            $systemLocale,
            NumberFormatter::CURRENCY
        );

        return $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
    }
}
