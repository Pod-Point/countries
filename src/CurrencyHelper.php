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
        $formatter = new NumberFormatter(
            $this->getSystemLocale($locale),
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
    public function getSymbol(string $currencyCode = 'GBP', string $locale = 'en')
    {
        $formatter = new NumberFormatter(
            $this->getSystemLocale($locale) . "@currency=$currencyCode",
            NumberFormatter::CURRENCY
        );

        return $formatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
    }

    /**
     * Return system locale from locale. (en => en_GB.UTF-8)
     *
     * @param string $locale
     * @return string
     */
    protected function getSystemLocale(string $locale)
    {
        $country = $this->countryHelper->findBy('locale', $locale);

        return $country['systemLocale'];
    }
}
