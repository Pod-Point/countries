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
     *
     * @return string
     */
    protected function getSystemLocale(string $locale): string
    {
        $country = $this->countryHelper->findBy('locale', $locale);

        return $country['systemLocale'];
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
        $country = $this->countryHelper->findBy('locale', $locale);

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
        $country = $this->countryHelper->findBy('locale', $locale);

        return $country['minorUnitEnd'] ?? null;
    }
}
