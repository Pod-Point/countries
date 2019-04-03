<?php

namespace PodPoint\I18n;

class CountryHelper extends Helper
{
    /**
     * Returns the first country matching the given property/value pair.
     *
     * @param string $property
     * @param $value
     *
     * @return array|null
     */
    public function findBy(string $property, $value)
    {
        $countries = $this->config->get('countries');

        foreach ($countries as $country) {
            if (array_get($country, $property) === $value) {
                return $country;
            }
        }

        return null;
    }
}
