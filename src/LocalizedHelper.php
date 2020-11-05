<?php

namespace PodPoint\I18n;

use Illuminate\Config\Repository;

abstract class LocalizedHelper extends Helper
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
     * Return system locale from locale. (en => en_GB.UTF-8)
     *
     * @param string $locale
     * @param bool $fallback
     *
     * @return string|null
     */
    protected function getSystemLocale(string $locale, $fallback = true): ?string
    {
        $country = $this->countryHelper->findByLocale($locale);

        return $country['systemLocale'] ?? ($fallback ? $this->getFallbackSystemLocale() : null);
    }

    /**
     * Return minor unit pattern from locale. (en => #.##p)
     *
     * @param string $locale
     *
     * @return string|null
     */
    protected function getMinorUnitPattern(string $locale): ?string
    {
        $country = $this->countryHelper->findByLocale($locale);

        return $country['minorUnitPattern'] ?? null;
    }

    /**
     * Return the last minor unit from locale. (en => 99)
     *
     * @param string $locale
     *
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
}
