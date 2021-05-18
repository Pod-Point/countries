<?php

namespace PodPoint\I18n;

class CountryHelper extends Helper
{
    /**
     * Returns the first country matching the given property/value pair.
     *
     * @param string $property
     * @param mixed $value
     *
     * @return array|null
     */
    public function findBy(string $property, $value): ?array
    {
        return collect($this->config->get('countries'))
            ->where($property, $value)
            ->first();
    }

    /**
     * Returns the first country matching the given locale.
     *
     * @param string $locale
     * @return array|null
     */
    public function findByLocale(string $locale): ?array
    {
        return $this->findBy('locale', $locale);
    }

    /**
     * Returns the country code matching the given locale.
     *
     * @param string $locale
     * @return string|null
     */
    public function getCountryCodeFromLocale(string $locale): ?string
    {
        $code = collect($this->config->get('countries'))
            ->search(function ($item) use ($locale) {
                return isset($item['locale']) && $item['locale'] === $locale;
            });

        return $code ? $code : null;
    }
}
